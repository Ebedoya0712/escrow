@extends('layouts.app')

@section('title', __('messages.admin_transaction_details.page_title'))
@section('page-title', __('messages.admin_transaction_details.page_title_id', ['id' => 'TR-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT)]))

@section('content')
<div class="row">
    <!-- Columna Izquierda: Chat -->
    <div class="col-lg-7 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm d-flex flex-column" style="height: 75vh;">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.admin_transaction_details.chat_title') }}</h5>
            </div>
            <div class="card-body flex-grow-1 p-3" style="overflow-y: auto;" id="chat-box">
                @if($transaction->status == 'cancelled')
                <div class="alert alert-danger" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>{{ __('messages.admin_transaction_details.cancelled_alert_title') }}</h5>
                    <p>{{ __('messages.admin_transaction_details.cancelled_alert_text') }}</p>
                </div>
                @endif
                @foreach ($transaction->messages as $message)
                    <div class="message-item" data-message-id="{{ $message->id }}">
                        <div class="d-flex flex-row {{ $message->user_id == Auth::id() ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                            <div>
                                <div class="fw-bold small {{ $message->user_id == Auth::id() ? 'text-end me-3' : 'ms-3' }}">{{ $message->user->name }}</div>
                                <p class="small p-2 mb-1 rounded-3 {{ $message->user_id == Auth::id() ? 'me-3 text-white bg-primary' : 'ms-3' }}" style="{{ $message->user_id != Auth::id() ? 'background-color: #f5f6f7;' : '' }}">{!! $message->content !!}</p>
                                <p class="small rounded-3 text-muted {{ $message->user_id == Auth::id() ? 'me-3 text-end' : 'ms-3' }}">{{ $message->created_at->format('h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="d-flex flex-row justify-content-start mb-3 d-none" id="typing-indicator">
                    <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;"><span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></p>
                </div>
            </div>
            @if($transaction->admin_joined_at && $transaction->status != 'cancelled')
            <div class="card-footer bg-white border-0 text-muted">
                <form id="message-form" action="{{ route('messages.store', $transaction) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group">
                        <input type="text" id="message-content" name="content" class="form-control border-0" placeholder="{{ __('messages.admin_transaction_details.admin_chat_placeholder') }}" autocomplete="off" style="background-color: #f5f6f7;">
                        <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Columna Derecha: Detalles y Acciones de Admin -->
    <div class="col-lg-5">
        @php
            $allowedToJoinStatuses = ['accepted', 'payment_verified', 'item_delivered', 'disputed'];
        @endphp
        @if(in_array($transaction->status, $allowedToJoinStatuses) && !$transaction->admin_joined_at)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body text-center">
                <h5 class="mb-3">{{ __('messages.admin_transaction_details.attention_needed') }}</h5>
                <button class="btn btn-success btn-lg" onclick="joinChat('{{ route('admin.transactions.joinChat', $transaction) }}')">
                    <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.admin_transaction_details.join_chat_button') }}
                </button>
            </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
             <div class="card-body">
                <h5 class="mb-3">{{ __('messages.admin_transaction_details.admin_actions') }}</h5>
                <form action="{{ route('admin.transactions.updateStatus', $transaction) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <select name="status" class="form-select">
                            @foreach(['pending', 'accepted', 'payment_verified', 'item_delivered', 'completed', 'cancelled', 'disputed'] as $status)
                                <option value="{{ $status }}" @if($transaction->status == $status) selected @endif>
                                    {{ __('messages.statuses.' . $status) }}
                                </option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary" type="submit">{{ __('messages.admin_transaction_details.update_button') }}</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($seller && $transaction->status != 'cancelled')
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.admin_transaction_details.seller_payment_info') }}</h5>
                <small class="text-muted">{{ $seller->name }}</small>
            </div>
            <div class="card-body">
                @forelse ($seller->paymentMethods as $method)
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $method->label }} <span class="badge bg-secondary">{{ $method->method_name }}</span></h6>
                        <p class="text-muted mb-0" style="white-space: pre-wrap;">{{ $method->details }}</p>
                    </div>
                    @if (!$loop->last) <hr class="my-3"> @endif
                @empty
                    <div class="alert alert-warning mb-0">{{ __('messages.admin_transaction_details.no_payment_methods') }}</div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<style>
    .typing-dot { 
        display: inline-block; 
        width: 8px; 
        height: 8px; 
        border-radius: 50%; 
        background-color: #888; 
        margin: 0 2px; 
        animation: typing 1s infinite; 
    }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 
        0%, 60%, 100% { transform: translateY(0); } 
        30% { transform: translateY(-5px); } 
    }
</style>
<script>
    function joinChat(url) {
        fetch(url, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                'Accept': 'application/json' 
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                Swal.fire({
                    title: '¡Éxito!',
                    text: '{{ __("messages.admin_transaction_details.join_chat_success") }}',
                    icon: 'success'
                }).then(() => location.reload());
            } else {
                Swal.fire({
                    title: 'Error',
                    text: data.error || '{{ __("messages.admin_transaction_details.join_chat_error") }}',
                    icon: 'error'
                });
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const chatBox = document.getElementById('chat-box');
        const messageForm = document.getElementById('message-form');
        const messageContentInput = document.getElementById('message-content');
        const typingIndicator = document.getElementById('typing-indicator');
        const currentUserId = {{ Auth::id() }};
        
        function scrollToBottom() {
            if (chatBox) { 
                chatBox.scrollTop = chatBox.scrollHeight; 
            }
        }

        function appendMessage(message) {
            const messageId = message.id;
            if (document.querySelector(`.message-item[data-message-id="${messageId}"]`)) { 
                return; 
            }
            
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message-item');
            messageDiv.dataset.messageId = messageId;
            
            const messageTime = new Date(message.created_at).toLocaleTimeString('es-ES', { 
                hour: '2-digit', 
                minute: '2-digit' 
            });
            
            const isCurrentUser = message.user_id == currentUserId;
            const justifyClass = isCurrentUser ? 'justify-content-end' : 'justify-content-start';
            const bubbleClass = isCurrentUser ? 'me-3 text-white bg-primary' : 'ms-3';
            const bubbleStyle = isCurrentUser ? '' : 'style="background-color: #f5f6f7;"';
            const textAlignment = isCurrentUser ? 'me-3 text-end' : 'ms-3';
            
            messageDiv.innerHTML = `
                <div class="d-flex flex-row ${justifyClass} mb-3">
                    <div>
                        <div class="fw-bold small ${textAlignment}">${message.user.name}</div>
                        <p class="small p-2 mb-1 rounded-3 ${bubbleClass}" ${bubbleStyle}>${message.content}</p>
                        <p class="small rounded-3 text-muted ${textAlignment}">${messageTime}</p>
                    </div>
                </div>
            `;
            
            chatBox.insertBefore(messageDiv, typingIndicator);
            scrollToBottom();
        }

        if (messageForm) {
            messageForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 
                        'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                        'Accept': 'application/json' 
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.id) {
                        this.reset();
                        scrollToBottom();
                    }
                });
            });
        }

        if (chatBox) {
            const chatChannel = Echo.private('chat.{{ $transaction->id }}');

            chatChannel.listen('.new-message', (e) => {
                appendMessage(e.message);
            });

            chatChannel.listen('.transaction-cancelled', (e) => {
                const redirectUrl = "{{ Auth::user()->role === 'admin' ? route('admin.transactions.index') : route('transacciones.index') }}";
                let alertText = "{{ __('messages.alerts.transaction_cancelled_by_user_text') }}".replace(':name', e.cancellingUser.name);
                
                Swal.fire({
                    icon: 'error', 
                    title: "{{ __('messages.alerts.transaction_cancelled_by_user_title') }}",
                    text: alertText,
                    allowOutsideClick: false, 
                    allowEscapeKey: false
                }).then(() => {
                    window.location.href = redirectUrl;
                });
            });

            chatChannel.listenForWhisper('typing', (e) => {
                if (e.userId !== currentUserId) {
                    typingIndicator.classList.remove('d-none');
                    scrollToBottom();
                    setTimeout(() => { 
                        typingIndicator.classList.add('d-none'); 
                    }, 3000);
                }
            });

            if(messageContentInput) {
                messageContentInput.addEventListener('input', () => {
                    chatChannel.whisper('typing', {
                        userId: currentUserId,
                        name: '{{ Auth::user()->name }}'
                    });
                });
            }

            scrollToBottom();
        }
    });
</script>
@endpush