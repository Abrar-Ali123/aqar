@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- قائمة المحادثات -->
                <div class="col-lg-4 border-end">
                    @include('messages.conversations-list')
                </div>

                <!-- منطقة المحادثة -->
                <div class="col-lg-8">
                    <div class="chat-container d-flex flex-column h-100">
                        <!-- رأس المحادثة -->
                        <div class="chat-header border-bottom p-3">
                            <div class="d-flex align-items-center">
                                @php
                                    $otherUser = $conversation->otherUser(auth()->id());
                                @endphp
                                
                                @if($otherUser->avatar)
                                    <img src="{{ Storage::url($otherUser->avatar) }}" 
                                         alt="{{ $otherUser->name }}"
                                         class="rounded-circle me-2"
                                         width="40" height="40">
                                @else
                                    <div class="avatar-placeholder rounded-circle me-2 d-flex align-items-center justify-content-center bg-primary text-white"
                                         style="width: 40px; height: 40px;">
                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                    </div>
                                @endif

                                <div>
                                    <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                    <small class="text-muted">
                                        @if($otherUser->is_online)
                                            <span class="text-success">
                                                <i class="fas fa-circle fa-xs"></i> متصل الآن
                                            </span>
                                        @else
                                            آخر ظهور {{ $otherUser->last_seen_at?->diffForHumans() }}
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- محتوى المحادثة -->
                        <div class="chat-messages flex-grow-1 p-3" id="chatMessages">
                            @foreach($messages->reverse() as $message)
                                <div class="message mb-3 {{ $message->isFromUser(auth()->id()) ? 'message-out' : 'message-in' }}">
                                    <div class="message-content">
                                        {{ $message->content }}
                                        <div class="message-meta">
                                            <small class="text-muted">
                                                {{ $message->created_at->format('H:i') }}
                                                @if($message->isFromUser(auth()->id()))
                                                    @if($message->read_at)
                                                        <i class="fas fa-check-double text-primary"></i>
                                                    @else
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                @endif
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- نموذج إرسال الرسائل -->
                        <div class="chat-input border-top p-3">
                            <form id="messageForm" class="d-flex align-items-center">
                                <input type="text" 
                                       class="form-control me-2" 
                                       placeholder="اكتب رسالتك هنا..."
                                       id="messageInput"
                                       required>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.chat-container {
    height: 600px;
}

.chat-messages {
    overflow-y: auto;
    display: flex;
    flex-direction: column;
}

.message {
    max-width: 70%;
    margin-bottom: 1rem;
}

.message-in {
    align-self: flex-start;
}

.message-out {
    align-self: flex-end;
}

.message-content {
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    position: relative;
}

.message-in .message-content {
    background-color: #f8f9fa;
    border-bottom-left-radius: 0.25rem;
}

.message-out .message-content {
    background-color: #007bff;
    color: white;
    border-bottom-right-radius: 0.25rem;
}

.message-meta {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    text-align: right;
}

.message-out .message-meta {
    color: rgba(255, 255, 255, 0.8);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('chatMessages');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const conversationId = '{{ $conversation->id }}';

    // تمرير إلى آخر الرسائل
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // إرسال رسالة جديدة
    messageForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const content = messageInput.value.trim();
        if (!content) return;

        try {
            const response = await fetch(`/messages/${conversationId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ content })
            });

            if (response.ok) {
                messageInput.value = '';
                const data = await response.json();
                appendMessage(data.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // الاستماع للرسائل الجديدة
    window.Echo.private(`conversation.${conversationId}`)
        .listen('.message.new', (e) => {
            if (e.sender_id !== {{ auth()->id() }}) {
                appendMessage(e);
                markAsRead();
            }
        });

    // إضافة رسالة جديدة للمحادثة
    function appendMessage(message) {
        const isOutgoing = message.sender_id === {{ auth()->id() }};
        const messageHtml = `
            <div class="message mb-3 ${isOutgoing ? 'message-out' : 'message-in'}">
                <div class="message-content">
                    ${message.content}
                    <div class="message-meta">
                        <small class="text-muted">
                            ${new Date(message.created_at).toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' })}
                            ${isOutgoing ? '<i class="fas fa-check"></i>' : ''}
                        </small>
                    </div>
                </div>
            </div>
        `;

        messagesContainer.insertAdjacentHTML('beforeend', messageHtml);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // تحديث حالة القراءة
    async function markAsRead() {
        try {
            await fetch(`/messages/${conversationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
        } catch (error) {
            console.error('Error:', error);
        }
    }
});
</script>
@endpush
