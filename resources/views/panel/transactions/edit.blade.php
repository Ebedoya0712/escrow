@extends('layouts.app')

@section('title', __('messages.edit_transaction.page_title'))
@section('page-title', __('messages.edit_transaction.form_title', ['id' => 'TR-' . str_pad($transaction->id, 4, '0', STR_PAD_LEFT)]))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form action="{{ route('transacciones.update', $transaction) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">{{ __('messages.create_transaction.field_title') }}</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $transaction->title) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-bold">{{ __('messages.create_transaction.field_description') }}</label>
                        <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $transaction->description) }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amount" class="form-label fw-bold">{{ __('messages.create_transaction.field_amount') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="amount" name="amount" step="0.01" min="1" value="{{ old('amount', $transaction->amount) }}" required>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('transacciones.index') }}" class="btn btn-secondary">{{ __('messages.edit_transaction.cancel_button') }}</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ __('messages.edit_transaction.submit_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

