@extends('layouts.app')

@section('title', __('messages.transaction_details.page_title'))
@section('page-title', __('messages.transaction_details.page_title_id', ['id' => 'TR-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT)]))

@section('page-actions')
    @if (in_array($transaction->status, ['pending', 'accepted']))
        <button type="button" class="btn btn-danger shadow-sm me-2" onclick="confirmCancel('{{ route('transacciones.cancel', $transaction) }}')">
            <i class="fas fa-times-circle me-1"></i>
            {{ __('messages.transaction_details.cancel_button') }}
        </button>
    @endif
    <a href="{{ route('transacciones.index') }}" class="btn btn-secondary shadow-sm">
        <i class="fas fa-arrow-left me-1"></i>
        {{ __('messages.transaction_details.back_button') }}
    </a>
@endsection

@section('content')
<div class="row">
    <!-- Columna Izquierda: Chat o Invitación -->
    <div class="col-lg-8 mb-4 mb-lg-0" 
         id="transaction-data"
         data-cancel-title="{{ __('messages.alerts.transaction_cancelled_by_user_title') }}"
         data-cancel-text="{{ __('messages.alerts.transaction_cancelled_by_user_text') }}">
        @if ($transaction->status == 'pending' && $transaction->creator_id == Auth::id())
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-warning-subtle border-0">
                    <h5 class="mb-0"><i class="fas fa-link me-2"></i>{{ __('messages.transaction_details.invitation_title') }}</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">{{ __('messages.transaction_details.invitation_text') }}</p>
                    <div class="input-group">
                        <input type="text" id="invitationLink" class="form-control" value="{{ $invitationUrl }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="copyLink()"><i class="fas fa-copy me-1"></i> {{ __('messages.transaction_details.copy_button') }}</button>
                    </div>
                </div>
            </div>
        @else
            <!-- Interfaz del Chat -->
            <div class="card border-0 shadow-sm d-flex flex-column" style="height: 75vh;">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="mb-0">{{ __('messages.transaction_details.chat_title') }}</h5>
                </div>
                
                <div class="card-body flex-grow-1 p-3" style="overflow-y: auto;" id="chat-box">
                    @foreach ($transaction->messages as $message)
                        <div class="message-item" data-message-id="{{ $message->id }}">
                            @if ($message->user_id != Auth::id())
                            <!-- Mensaje Recibido -->
                            <div class="d-flex flex-row justify-content-start mb-3">
                                <div class="message-wrapper">
                                    <p class="small p-2 ms-3 mb-1 rounded-3 message-bubble" style="background-color: #f5f6f7;">{!! $message->content !!}</p>
                                    <p class="small ms-3 rounded-3 text-muted">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                            @else
                            <!-- Mensaje Enviado -->
                            <div class="d-flex flex-row justify-content-end mb-3">
                                <div class="message-wrapper">
                                    <p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary message-bubble">{!! $message->content !!}</p>
                                    <p class="small me-3 rounded-3 text-muted text-end">{{ $message->created_at->format('h:i A') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endforeach
                    <!-- Indicador de "Escribiendo..." -->
                    <div class="d-flex flex-row justify-content-start mb-3 d-none" id="typing-indicator">
                        <div class="message-wrapper">
                            <p class="small p-2 ms-3 mb-1 rounded-3" style="background-color: #f5f6f7;">
                                <span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer con Formulario para Enviar Mensaje -->
                <div class="card-footer bg-white border-0 text-muted">
                    @if($transaction->status == 'accepted' && !$transaction->admin_joined_at)
                    <div id="waiting-for-admin" class="text-center">
                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                        <p class="mt-2 mb-0">{{ __('messages.transaction_details.waiting_for_admin') }}</p>
                    </div>
                    @endif

                    <form id="message-form" action="{{ route('messages.store', $transaction) }}" method="POST" enctype="multipart/form-data" class="{{ ($transaction->status == 'accepted' && !$transaction->admin_joined_at) || $transaction->status == 'cancelled' ? 'd-none' : '' }}">
                        @csrf
                        <div id="attachment-preview-container" class="mb-2 d-none">
                            <div class="d-inline-block position-relative">
                                <img id="attachment-preview-img" src="" alt="Vista previa" style="max-height: 70px; border-radius: 5px;">
                                <button type="button" id="remove-attachment-btn" class="btn-close btn-sm position-absolute top-0 end-0 bg-light rounded-circle" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="input-group">
                            <input type="text" id="message-content" name="content" class="form-control border-0" placeholder="{{ $transaction->status == 'cancelled' ? __('messages.transaction_details.chat_closed') : __('messages.transaction_details.type_your_message') }}" autocomplete="off" style="background-color: #f5f6f7;">
                            <label class="btn btn-light" for="attachment-input" title="{{ __('messages.transaction_details.attach_file') }}"><i class="fas fa-paperclip"></i></label>
                            <input type="file" name="attachment" id="attachment-input" class="d-none">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <!-- Columna Derecha: Detalles, Proceso y Métodos de Pago -->
    <div class="col-lg-4">
        @if($transaction->status == 'accepted' && !$transaction->admin_joined_at)
        <div id="countdown-card" class="card border-0 shadow-sm mb-4 text-center">
            <div class="card-body">
                <h5 class="mb-3">{{ __('messages.transaction_details.estimated_wait_time') }}</h5>
                <p class="display-4 fw-bold" id="countdown-timer">3:00</p>
                <small class="text-muted">{{ __('messages.transaction_details.admin_will_join') }}</small>
            </div>
        </div>
        @endif

        <div class="card border-0 shadow-sm mb-4">
             <div class="card-body">
                <h5 class="mb-3">{{ __('messages.transaction_details.details_title') }}</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between px-0"><strong>{{ __('messages.table.title') }}:</strong> <span>{{ $transaction->title }}</span></li>
                    <li class="list-group-item d-flex justify-content-between px-0"><strong>{{ __('messages.table.amount') }}:</strong> <span class="fw-bold">${{ number_format($transaction->amount, 2) }}</span></li>
                    <li class="list-group-item d-flex justify-content-between px-0"><strong>{{ __('messages.table.status') }}:</strong> <span id="status-badge" class="badge bg-info-subtle text-info-emphasis rounded-pill">{{ __('messages.statuses.' . $transaction->status) }}</span></li>
                </ul>
                <hr>
                <h6 class="mb-3">{{ __('messages.transaction_details.participants_title') }}</h6>
                @foreach ($transaction->participants as $participant)
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-user-circle fa-2x text-secondary me-3"></i>
                    <div>
                        <h6 class="mb-0">{{ $participant->name }}</h6>
                        <small class="text-muted">{{ $participant->pivot->role == 'seller' ? __('messages.transactions_list.role_seller') : __('messages.transactions_list.role_buyer') }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.transaction_details.process_title') }}</h5>
            </div>
            <div class="card-body">
                <ul class="timeline">
                    @foreach($processSteps as $status => $label)
                        @php
                            $stepIndex = array_search($status, array_keys($processSteps));
                            $isActive = $currentStepIndex !== false && $stepIndex <= $currentStepIndex;
                        @endphp
                        <li class="timeline-item {{ $isActive ? 'active' : '' }}">
                            <strong>{{ __($label) }}</strong>
                            @if($transaction->status == $status)
                                <small class="text-muted d-block">{{ __('messages.transaction_details.current_step') }}</small>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        @php
            $currentUserRole = $transaction->participants->firstWhere('id', Auth::id())->pivot->role;
        @endphp
        @if($currentUserRole == 'buyer' && in_array($transaction->status, ['accepted', 'payment_verified']))
        <div class="accordion" id="paymentMethodsAccordion">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 p-0" id="headingOne">
                    <h2 class="mb-0">
                        <button class="btn btn-light w-100 text-start d-flex justify-content-between align-items-center p-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <span><i class="fas fa-money-check-alt me-2"></i> {{ __('messages.transaction_details.payment_method_title') }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </h2>
                </div>
                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#paymentMethodsAccordion">
                    <div class="card-body">
                        <p class="text-muted">{{ __('messages.transaction_details.payment_method_text') }}</p>
                        @forelse($platformPaymentMethods as $method)
                            <div>
                                <h6 class="mb-0 fw-bold">{{ $method->label }} <span class="badge bg-secondary">{{ $method->method_name }}</span></h6>
                                <p class="text-muted mb-1" style="white-space: pre-wrap;">{{ $method->details }}</p>
                                @if($method->instructions)
                                    <small class="d-block text-info fst-italic"><strong>{{ __('messages.transaction_details.instructions') }}:</strong> {{ $method->instructions }}</small>
                                @endif
                            </div>
                            @if (!$loop->last) <hr class="my-3"> @endif
                        @empty
                            <p class="text-center text-muted">{{ __('messages.transaction_details.no_payment_methods') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<style>
    .timeline { list-style: none; padding: 0; position: relative; }
    .timeline:before { content: ''; position: absolute; top: 0; bottom: 0; width: 2px; background: #e9ecef; left: 10px; margin-left: -1.5px; }
    .timeline-item { margin-bottom: 20px; position: relative; padding-left: 35px; }
    .timeline-item:before { content: '\f00c'; font-family: 'Font Awesome 5 Free'; font-weight: 900; background: #ced4da; color: white; position: absolute; left: 10px; width: 25px; height: 25px; border-radius: 50%; margin-left: -12.5px; top: 0px; transition: background-color 0.3s ease; display: flex; align-items: center; justify-content: center; font-size: 12px; }
    .timeline-item.active strong { color: #0d6efd; }
    .timeline-item.active:before { background: #0d6efd; }
    .typing-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background-color: #888; margin: 0 2px; animation: typing 1s infinite; }
    .typing-dot:nth-child(2) { animation-delay: 0.2s; }
    .typing-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 0%, 60%, 100% { transform: translateY(0); } 30% { transform: translateY(-5px); } }
    .message-wrapper { max-width: 75%; }
    .message-bubble { overflow-wrap: break-word; word-wrap: break-word; word-break: break-word; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chatBox = document.getElementById('chat-box');
    const messageForm = document.getElementById('message-form');
    const messageContentInput = document.getElementById('message-content');
    const typingIndicator = document.getElementById('typing-indicator');
    const attachmentInput = document.getElementById('attachment-input');
    const previewContainer = document.getElementById('attachment-preview-container');
    const previewImg = document.getElementById('attachment-preview-img');
    const removeAttachmentBtn = document.getElementById('remove-attachment-btn');
    const currentUserId = {{ Auth::id() }};
    
    function scrollToBottom() {
        if (chatBox) { chatBox.scrollTop = chatBox.scrollHeight; }
    }

    function appendMessage(message) {
        const messageId = message.id;
        if (document.querySelector(`.message-item[data-message-id="${messageId}"]`)) { return; }
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message-item');
        messageDiv.dataset.messageId = messageId;
        const messageTime = new Date(message.created_at).toLocaleTimeString('es-ES', { hour: '2-digit', minute: '2-digit' });
        let messageHtml = '';

        if (message.user_id != currentUserId) {
            messageHtml = `<div class="d-flex flex-row justify-content-start mb-3"><div class="message-wrapper"><p class="small p-2 ms-3 mb-1 rounded-3 message-bubble" style="background-color: #f5f6f7;">${message.content}</p><p class="small ms-3 rounded-3 text-muted">${messageTime}</p></div></div>`;
        } else {
            messageHtml = `<div class="d-flex flex-row justify-content-end mb-3"><div class="message-wrapper"><p class="small p-2 me-3 mb-1 text-white rounded-3 bg-primary message-bubble">${message.content}</p><p class="small me-3 rounded-3 text-muted text-end">${messageTime}</p></div></div>`;
        }
        messageDiv.innerHTML = messageHtml;
        chatBox.insertBefore(messageDiv, typingIndicator);
    }

    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if(data.id) {
                    appendMessage(data);
                    scrollToBottom();
                    this.reset();
                    previewContainer.classList.add('d-none');
                }
            });
        });
    }

    if (chatBox) {
        const chatChannel = Echo.private('chat.{{ $transaction->id }}');

        chatChannel.listen('.new-message', (e) => {
            if (e.message.user_id != currentUserId) {
                typingIndicator.classList.add('d-none');
                appendMessage(e.message);
                scrollToBottom();
            }
        });

        chatChannel.listenForWhisper('typing', (e) => {
            if (e.userId !== currentUserId) {
                typingIndicator.classList.remove('d-none');
                scrollToBottom();
                setTimeout(() => { typingIndicator.classList.add('d-none'); }, 3000);
            }
        });

        chatChannel.listen('.status-updated', (e) => {
    const newStatus = e.transaction.status.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
    document.getElementById('status-badge').textContent = newStatus;
    
    Swal.fire({
        icon: 'info',
        title: window.translations.status_updated_title || 'Estado Actualizado',
        text: (window.translations.status_updated_text || 'El estado de la transacción ahora es: :status').replace(':status', newStatus),
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000
    });
});

        chatChannel.listen('.transaction-cancelled', (e) => {
            const redirectUrl = "{{ Auth::user()->role === 'admin' ? route('admin.transactions.index') : route('transacciones.index') }}";
            let alertText = (window.translations.transaction_cancelled_by_user_text || ':name has cancelled the transaction. You will be redirected.').replace(':name', e.cancellingUser.name);
            Swal.fire({
                icon: 'error', 
                title: window.translations.transaction_cancelled_by_user_title || 'Transaction Cancelled',
                text: alertText,
                allowOutsideClick: false, 
                allowEscapeKey: false
            }).then(() => {
                window.location.href = redirectUrl;
            });
        });

        if(messageContentInput) {
            messageContentInput.addEventListener('input', () => {
                chatChannel.whisper('typing', {
                    userId: currentUserId,
                    name: '{{ Auth::user()->name }}'
                });
            });
        }

        setTimeout(scrollToBottom, 150);
    }

    const countdownCard = document.getElementById('countdown-card');
    if (countdownCard) {
        let timeLeft = 180;
        const timerElement = document.getElementById('countdown-timer');
        const countdownInterval = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                timerElement.textContent = "0:00";
                return;
            }
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            let seconds = timeLeft % 60;
            seconds = seconds < 10 ? '0' + seconds : seconds;
            timerElement.textContent = `${minutes}:${seconds}`;
        }, 1000);

        Echo.private('chat.{{ $transaction->id }}')
            .listen('.admin-joined', (e) => {
                clearInterval(countdownInterval);
                countdownCard.classList.add('d-none');
                document.getElementById('waiting-for-admin').classList.add('d-none');
                messageForm.classList.remove('d-none');
                Swal.fire({
                    icon: 'info', title: 'Un administrador se ha unido',
                    text: 'Ahora puedes continuar la conversación.',
                    toast: true, position: 'top-end',
                    showConfirmButton: false, timer: 3500
                });
            });
    }

    if (attachmentInput) {
        attachmentInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewContainer.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    }
    
    if (removeAttachmentBtn) {
        removeAttachmentBtn.addEventListener('click', function() {
            attachmentInput.value = '';
            previewContainer.classList.add('d-none');
        });
    }
});

function copyLink() {
    const linkInput = document.getElementById('invitationLink');
    linkInput.select();
    document.execCommand('copy');
    Swal.fire({ 
        toast: true, 
        position: 'top-end', 
        icon: 'success', 
        title: "{{ __('messages.transaction_details.link_copied_success') }}", 
        showConfirmButton: false, 
        timer: 2000 
    });
}
</script>
@endpush
