<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'commentable_type' => 'required|string|in:product,facility',
            'commentable_id' => 'required|integer'
        ]);

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->content,
            'commentable_type' => 'App\\Models\\' . ucfirst($request->commentable_type),
            'commentable_id' => $request->commentable_id
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم إضافة التعليق بنجاح'),
            'data' => $comment->load('user')
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $comment->update([
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم تحديث التعليق بنجاح'),
            'data' => $comment
        ]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => __('تم حذف التعليق بنجاح')
        ]);
    }

    public function reply(Request $request, Comment $comment)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        $reply = $comment->replies()->create([
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);

        return response()->json([
            'success' => true,
            'message' => __('تم إضافة الرد بنجاح'),
            'data' => $reply->load('user')
        ]);
    }
}
