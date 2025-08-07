@extends('layouts.auth') {{-- Asumiendo que tienes un layout para la autenticación --}}

@section('title', 'Recuperar Contraseña')

@section('content')
<h3 class="fw-bold mb-2">¿Olvidaste tu contraseña?</h3>
<p class="text-muted">No hay problema. Ingresa tu correo y te enviaremos un enlace para recuperarla.</p>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label fw-bold">Correo Electrónico</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
        @error('email')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary btn-submit btn-lg">
        Enviar Enlace de Recuperación
    </button>
    <div class="text-center mt-4">
        <p class="text-muted">
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold" style="color: var(--primary-color);">
                Volver a Iniciar Sesión
            </a>
        </p>
    </div>
</form>
@endsection
