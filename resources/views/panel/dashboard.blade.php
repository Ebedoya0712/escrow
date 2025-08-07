@extends('layouts.app')

{{-- El título cambia según el rol del usuario y el idioma --}}
@section('title', Auth::user()->role === 'admin' ? __('messages.dashboard.general_dashboard') : __('messages.dashboard.my_dashboard'))
@section('page-title', Auth::user()->role === 'admin' ? __('messages.dashboard.general_dashboard') : __('messages.dashboard.my_dashboard'))

{{-- El botón de "Nueva Transacción" solo aparece para usuarios normales --}}
@if(Auth::user()->role !== 'admin')
    @section('page-actions')
        <a href="{{ route('transacciones.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle me-1"></i>
            {{ __('messages.dashboard.new_transaction') }}
        </a>
    @endsection
@endif

@section('content')
    {{-- Tarjeta de Bienvenida --}}
    <div class="card bg-primary text-white border-0 shadow-lg mb-4" style="background: linear-gradient(135deg, #0d6efd, #0a58ca);">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-1">{{ __('messages.dashboard.welcome', ['name' => Auth::user()->name]) }}</h4>
                    <span class="text-white-75">
                        @if(Auth::user()->role === 'admin')
                            {{ __('messages.dashboard.admin_summary') }}
                        @else
                            {{ __('messages.dashboard.user_summary') }}
                        @endif
                    </span>
                </div>
                <i class="fas fa-chart-line fa-3x opacity-50"></i>
            </div>
        </div>
    </div>

    {{-- Tarjetas de Estadísticas --}}
    <div class="row">
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-exchange-alt fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">{{ __('messages.dashboard.stats.active') }}</p>
                        <h4 class="fw-bold mb-0">{{ $activeTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-check-circle fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">{{ __('messages.dashboard.stats.completed') }}</p>
                        <h4 class="fw-bold mb-0">{{ $completedTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-exclamation-triangle fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">{{ __('messages.dashboard.stats.disputed') }}</p>
                        <h4 class="fw-bold mb-0">{{ $disputedTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-times-circle fa-lg"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted mb-1">{{ __('messages.dashboard.stats.cancelled') }}</p>
                        <h4 class="fw-bold mb-0">{{ $cancelledTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Transacciones Recientes --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 pt-3">
            <h5 class="mb-0">
                @if(Auth::user()->role === 'admin')
                    {{ __('messages.dashboard.recent_transactions.admin_title') }}
                @else
                    {{ __('messages.dashboard.recent_transactions.user_title') }}
                @endif
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">{{ __('messages.table.id') }}</th>
                            <th scope="col">{{ __('messages.table.title') }}</th>
                            <th scope="col">{{ __('messages.table.amount') }}</th>
                            <th scope="col">{{ __('messages.table.status') }}</th>
                            <th scope="col">{{ __('messages.table.date') }}</th>
                            <th scope="col">{{ __('messages.table.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentTransactions as $transaction)
                            <tr>
                                <td class="fw-bold">#TR-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $transaction->title }}</td>
                                <td>${{ number_format($transaction->amount, 2) }} USD</td>
                                <td>
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill">
                                        {{ __('messages.statuses.' . $transaction->status) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                                <td>
                                    @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.table.view_button') }}</a>
                                    @else
                                        <a href="{{ route('transacciones.show', $transaction) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.table.view_button') }}</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">{{ __('messages.table.no_recent') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
