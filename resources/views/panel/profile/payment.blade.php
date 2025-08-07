@extends('layouts.app')

@section('title', __('messages.payment_methods.page_title'))
@section('page-title', __('messages.payment_methods.page_subtitle'))

@section('content')
<div class="row">
    <!-- Formulario para añadir o editar método -->
    <div class="col-lg-5 mb-4 mb-lg-0">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                @if(Auth::user()->role === 'admin')
                    <h5 class="mb-0">{{ __('messages.payment_methods.admin_add_method') }}</h5>
                @else
                    <h5 class="mb-0">{{ $paymentMethod ? __('messages.payment_methods.edit_method') : __('messages.payment_methods.add_method') }}</h5>
                @endif
            </div>
            <div class="card-body">
                <form action="{{ route('profile.payment.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="method_name" class="form-label fw-bold">{{ __('messages.payment_methods.form.method_type') }}</label>
                        <select class="form-select" name="method_name" id="method_name" required>
                            <option value="PayPal" @if(optional($paymentMethod)->method_name == 'PayPal') selected @endif>
                                {{ __('messages.payment_methods.methods.paypal') }}
                            </option>
                            <option value="AirTM" @if(optional($paymentMethod)->method_name == 'AirTM') selected @endif>
                                {{ __('messages.payment_methods.methods.airtm') }}
                            </option>
                            <option value="Banco Nacional" @if(optional($paymentMethod)->method_name == 'Banco Nacional') selected @endif>
                                {{ __('messages.payment_methods.methods.national_bank') }}
                            </option>
                            <option value="Otro" @if(optional($paymentMethod)->method_name == 'Otro') selected @endif>
                                {{ __('messages.payment_methods.methods.other') }}
                            </option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="label" class="form-label fw-bold">{{ __('messages.payment_methods.form.method_label') }}</label>
                        <input type="text" class="form-control" name="label" id="label" 
                               placeholder="{{ __('messages.payment_methods.form.method_label_placeholder') }}" 
                               value="{{ old('label', optional($paymentMethod)->label) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label fw-bold">{{ __('messages.payment_methods.form.method_details') }}</label>
                        <textarea class="form-control" name="details" id="details" rows="3" 
                                  placeholder="{{ __('messages.payment_methods.form.method_details_placeholder') }}" 
                                  required>{{ old('details', optional($paymentMethod)->details) }}</textarea>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> 
                            {{ $paymentMethod && Auth::user()->role !== 'admin' ? __('messages.payment_methods.form.update_button') : __('messages.payment_methods.form.save_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lista de métodos guardados -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.payment_methods.saved_methods') }}</h5>
            </div>
            <div class="card-body">
                @forelse ($paymentMethods as $method)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $method->label }} <span class="badge bg-secondary">{{ $method->method_name }}</span></h6>
                            <p class="text-muted mb-0 small">{{ $method->details }}</p>
                        </div>
                        @if(Auth::user()->role === 'admin')
                        <form action="{{ route('profile.payment.destroy', $method) }}" method="POST" 
                              onsubmit="return confirm('{{ __('messages.payment_methods.delete_confirm.text') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                        @endif
                    </div>
                    @if (!$loop->last) <hr> @endif
                @empty
                    <p class="text-center text-muted">{{ __('messages.payment_methods.no_methods') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection