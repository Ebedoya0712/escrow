@extends('layouts.app')

@section('title', __('messages.profile.page_title'))
@section('page-title', __('messages.profile.page_subtitle'))

@section('content')
<div class="row">
    <div class="col-lg-7">
        <!-- Profile Information Form -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.profile.profile_info.title') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update.details') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">{{ __('messages.profile.profile_info.full_name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label fw-bold">{{ __('messages.profile.profile_info.email') }}</label>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.profile.profile_info.save_changes') }}</button>
                </form>
            </div>
        </div>

        <!-- Change Password Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="mb-0">{{ __('messages.profile.change_password.title') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update.password') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="current_password" class="form-label fw-bold">{{ __('messages.profile.change_password.current_password') }}</label>
                        <input type="password" class="form-control" id="current_password" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">{{ __('messages.profile.change_password.new_password') }}</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label fw-bold">{{ __('messages.profile.change_password.confirm_password') }}</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('messages.profile.change_password.update_password') }}</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Payment Methods Section -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('messages.profile.payment_methods.title') }}</h5>
                <a href="{{ route('profile.payment.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-edit me-1"></i> {{ __('messages.profile.payment_methods.manage_button') }}
                </a>
            </div>
            <div class="card-body">
                @forelse ($paymentMethods as $method)
                    <div>
                        <h6 class="mb-0 fw-bold">{{ $method->label }} <span class="badge bg-secondary">{{ $method->method_name }}</span></h6>
                        <p class="text-muted mb-0 small">{{ $method->details }}</p>
                    </div>
                    @if (!$loop->last) <hr class="my-3"> @endif
                @empty
                    <p class="text-center text-muted">{{ __('messages.profile.payment_methods.no_methods') }}</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection