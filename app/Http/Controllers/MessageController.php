<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;
use App\Events\NewMessage;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\TranslatableController;

class MessageController extends TranslatableController
{
    protected $translatableFields = [
        'subject' => ['required', 'string', 'max:255'],
        'content' => ['required', 'string'],
    ];

    /**
     * عرض قائمة المحادثات
     */
    public function index(): View
    {
        if (!Auth::user()->can('view messages')) {
            return redirect()->back()
                ->with('error', __('messages.unauthorized_action'));
        }

        $conversations = auth()->user()->conversations()
            ->with(['userOne', 'userTwo'])
            ->latest('last_message_at')
            ->get();

        $query = Message::with(['sender', 'recipient']);

        // Filter messages based on user role
        if (!Auth::user()->hasRole('admin')) {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())
                    ->orWhere('recipient_id', Auth::id());
            });
        }

        $messages = $query->latest()->paginate(15);
        return view('messages.index', compact('conversations', 'messages'));
    }

    /**
     * عرض محادثة محددة
     */
    public function show(Conversation $conversation): View
    {
        // التحقق من أن المستخدم جزء من المحادثة
        abort_if(!$this->isUserInConversation($conversation), 403);

        $messages = $conversation->messages()
            ->with('sender')
            ->latest()
            ->paginate(50);

        // تحديث حالة القراءة للرسائل
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $message = new Message();
        $translations = $this->prepareTranslations($message, array_keys($this->translatableFields));
        return view('messages.show', compact('conversation', 'messages', 'translations'));
    }

    /**
     * إنشاء محادثة جديدة
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $otherUser = User::findOrFail($request->user_id);
        
        // التحقق من وجود محادثة سابقة
        $conversation = $this->findExistingConversation($otherUser->id);

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one' => auth()->id(),
                'user_two' => $otherUser->id,
                'last_message_at' => now()
            ]);
        }

        return response()->json([
            'conversation_id' => $conversation->id
        ]);
    }

    /**
     * إرسال رسالة جديدة
     */
    public function sendMessage(Request $request, Conversation $conversation): JsonResponse
    {
        // التحقق من أن المستخدم جزء من المحادثة
        abort_if(!$this->isUserInConversation($conversation), 403);

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'content' => $request->content
        ]);

        $conversation->update(['last_message_at' => now()]);

        // بث الرسالة
        broadcast(new NewMessage($message))->toOthers();

        $this->handleTranslations($message, $request, array_keys($this->translatableFields));

        return response()->json([
            'message' => $message->load('sender')
        ]);
    }

    /**
     * تحديث حالة قراءة الرسائل
     */
    public function markAsRead(Conversation $conversation): JsonResponse
    {
        // التحقق من أن المستخدم جزء من المحادثة
        abort_if(!$this->isUserInConversation($conversation), 403);

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json([
            'success' => true
        ]);
    }

    /**
     * التحقق من أن المستخدم جزء من المحادثة
     */
    private function isUserInConversation(Conversation $conversation): bool
    {
        return in_array(auth()->id(), [$conversation->user_one, $conversation->user_two]);
    }

    /**
     * البحث عن محادثة موجودة بين المستخدمين
     */
    private function findExistingConversation($otherUserId): ?Conversation
    {
        return Conversation::where(function($query) use ($otherUserId) {
            $query->where('user_one', auth()->id())
                  ->where('user_two', $otherUserId);
        })->orWhere(function($query) use ($otherUserId) {
            $query->where('user_one', $otherUserId)
                  ->where('user_two', auth()->id());
        })->first();
    }
}
