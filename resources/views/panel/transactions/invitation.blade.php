@extends('layouts.app')

@section('title', __('messages.invitation.page_title'))
@section('page-title', __('messages.invitation.page_title'))

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body p-4 p-md-5">
                <i class="fas fa-handshake fa-3x text-primary mb-3"></i>
                <h3 class="card-title mb-2">{{ __('messages.invitation.invited_by', ['name' => $creator->name]) }}</h3>
                <p class="text-muted">{{ __('messages.invitation.review_details') }}</p>
                
                <hr class="my-4">

                <dl class="row text-start">
                    <dt class="col-sm-4">{{ __('messages.invitation.details_title') }}</dt>
                    <dd class="col-sm-8">{{ $transaction->title }}</dd>

                    <dt class="col-sm-4">{{ __('messages.invitation.details_amount') }}</dt>
                    <dd class="col-sm-8 fw-bold fs-5">${{ number_format($transaction->amount, 2) }} USD</dd>

                    <dt class="col-sm-4">{{ __('messages.invitation.details_your_role') }}</dt>
                    <dd class="col-sm-8"><span class="badge bg-success fs-6">{{ $inviteeRole }}</span></dd>
                </dl>

                <hr class="my-4">

                @auth
                    @if ($transaction->participants->contains(Auth::user()))
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-1"></i> {{ __('messages.invitation.already_participant') }}
                        </div>
                        <a href="{{ route('transacciones.show', $transaction) }}" class="btn btn-primary">{{ __('messages.invitation.view_transaction_button') }}</a>
                    @else
                        @if ($transaction->status == 'pending')
                            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                                <form action="{{ route('transactions.accept', $transaction->uuid) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-lg px-4 gap-3">
                                        <i class="fas fa-check-circle me-2"></i>{{ __('messages.invitation.accept_button') }}
                                    </button>
                                </form>
                                <a href="{{ route('dashboard') }}" class="btn btn-danger btn-lg px-4">
                                    <i class="fas fa-times-circle me-2"></i>{{ __('messages.invitation.reject_button') }}
                                </a>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                {{ __('messages.invitation.no_longer_pending') }}
                            </div>
                        @endif
                    @endif
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i> 
                        {!! __('messages.invitation.auth_required', [
                            'login_url' => route('login'),
                            'register_url' => route('register')
                        ]) !!}
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection