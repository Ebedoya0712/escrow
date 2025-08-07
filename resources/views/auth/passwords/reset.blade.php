@extends('layouts.auth')

@section('title', 'Restablecer Contraseña')

@section('content')
<h3 class="fw-bold mb-2">Restablecer tu Contraseña</h3>
<p class="text-muted">Crea una nueva contraseña segura para tu cuenta.</p>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label for="email" class="form-label fw-bold">Correo Electrónico</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ $email ?? old('email') }}">
        @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password" class="form-label fw-bold">Nueva Contraseña</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
        @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="password-confirm" class="form-label fw-bold">Confirmar Nueva Contraseña</label>
        <input type="password" class="form-control" id="password-confirm" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn btn-primary btn-submit btn-lg">
        Restablecer Contraseña
    </button>
</form>
@endsection
