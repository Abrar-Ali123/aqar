@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card">
        <div class="card-body p-0">
            <div class="row g-0">
                <!-- قائمة المحادثات -->
                <div class="col-lg-4 border-end">
                    <div class="conversations-list">
                        @forelse($conversations as $conversation)
                            @php
                                $otherUser = $conversation->otherUser(auth()->id());
                                $lastMessage = $conversation->lastMessage();
                                $unreadCount = $conversation->unreadCount(auth()->id());
                            @endphp
                            <a href="{{ route('messages.show', $conversation) }}" 
                               class="conversation-item d-flex align-items-center p-3 border-bottom text-decoration-none {{ request()->conversation?->id === $conversation->id ? 'active' : '' }}">
                                <!-- صورة المستخدم -->
                                @if($otherUser->avatar)
                                    <img src="{{ Storage::url($otherUser->avatar) }}" 
                                         alt="{{ $otherUser->name }}"
                                         class="rounded-circle me-3"
                                         width="50" height="50">
                                @else
                                    <div class="avatar-placeholder rounded-circle me-3 d-flex align-items-center justify-content-center bg-primary text-white"
                                         style="width: 50px; height: 50px;">
                                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                    </div>
                                @endif

                                <!-- معلومات المحادثة -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0">{{ $otherUser->name }}</h6>
                                        <small class="text-muted">
                                            {{ $conversation->last_message_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    @if($lastMessage)
                                        <p class="text-muted small mb-0">
                                            {{ Str::limit($lastMessage->content, 50) }}
                                        </p>
                                    @endif
                                </div>

                                <!-- عدد الرسائل غير المقروءة -->
                                @if($unreadCount > 0)
                                    <span class="badge bg-primary rounded-pill ms-2">
                                        {{ $unreadCount }}
                                    </span>
                                @endif
                            </a>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-comments text-muted fa-3x mb-3"></i>
                                <h5>لا توجد محادثات</h5>
                                <p class="text-muted">ابدأ محادثة جديدة مع أحد المستخدمين</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- منطقة المحادثة -->
                <div class="col-lg-8">
                    @if(request()->conversation)
                        <!-- سيتم تحميل المحادثة هنا -->
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-paper-plane text-muted fa-3x mb-3"></i>
                            <h5>اختر محادثة</h5>
                            <p class="text-muted">اختر محادثة من القائمة لعرض الرسائل</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.conversations-list {
    height: 600px;
    overflow-y: auto;
}

.conversation-item {
    transition: background-color 0.2s;
}

.conversation-item:hover {
    background-color: #f8f9fa;
}

.conversation-item.active {
    background-color: #e9ecef;
}

.avatar-placeholder {
    font-size: 1.5rem;
}
</style>
@endpush
