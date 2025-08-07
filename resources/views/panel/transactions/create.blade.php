@extends('layouts.app')

@section('title', __('messages.create_transaction.page_title'))
@section('page-title', __('messages.create_transaction.form_title'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('transacciones.store') }}" method="POST">
                    @csrf
                    
                    {{-- Título de la Transacción --}}
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">{{ __('messages.create_transaction.field_title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('messages.create_transaction.field_title_placeholder') }}" value="{{ old('title') }}" required>
                        <div class="form-text">{{ __('messages.create_transaction.field_title_help') }}</div>
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">{{ __('messages.create_transaction.field_description') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="5" placeholder="{{ __('messages.create_transaction.field_description_placeholder') }}" required>{{ old('description') }}</textarea>
                    </div>

                    <div class="row">
                        {{-- Monto --}}
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label fw-bold">{{ __('messages.create_transaction.field_amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" placeholder="50.00" step="0.01" min="1" value="{{ old('amount') }}" required>
                            </div>
                        </div>

                        {{-- Tu Rol en la Transacción --}}
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label fw-bold">{{ __('messages.create_transaction.field_role') }}</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="" selected disabled>{{ __('messages.create_transaction.role_option_select') }}</option>
                                <option value="seller" @if(old('role') == 'seller') selected @endif>{{ __('messages.create_transaction.role_option_seller') }}</option>
                                <option value="buyer" @if(old('role') == 'buyer') selected @endif>{{ __('messages.create_transaction.role_option_buyer') }}</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('messages.create_transaction.submit_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
