@extends('layouts.app')

@section('title', __('messages.transactions_list.page_title'))
@section('page-title', __('messages.transactions_list.page_title'))

@section('page-actions')
    <a href="{{ route('transacciones.create') }}" class="btn btn-primary shadow-sm">
        <i class="fas fa-plus-circle me-1"></i>
        {{ __('messages.transactions_list.new_transaction_button') }}
    </a>
@endsection

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="transactionsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">{{ __('messages.table.id') }}</th>
                        <th scope="col">{{ __('messages.table.title') }}</th>
                        <th scope="col">{{ __('messages.table.amount') }}</th>
                        <th scope="col">{{ __('messages.transactions_list.table_my_role') }}</th>
                        <th scope="col">{{ __('messages.table.status') }}</th>
                        <th scope="col">{{ __('messages.table.date') }}</th>
                        <th scope="col">{{ __('messages.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $transaction)
                        <tr>
                            <td class="fw-bold">#TR-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $transaction->title }}</td>
                            <td>${{ number_format($transaction->amount, 2) }} USD</td>
                            <td>
                                @if ($transaction->pivot->role == 'seller')
                                    <span class="badge bg-primary-subtle text-primary-emphasis">{{ __('messages.transactions_list.role_seller') }}</span>
                                @else
                                    <span class="badge bg-success-subtle text-success-emphasis">{{ __('messages.transactions_list.role_buyer') }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($transaction->status == 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger-emphasis rounded-pill">{{ __('messages.statuses.' . $transaction->status) }}</span>
                                @else
                                    <span class="badge bg-info-subtle text-info-emphasis rounded-pill">{{ __('messages.statuses.' . $transaction->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('transacciones.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="{{ __('messages.transactions_list.action_view') }}"><i class="fas fa-eye"></i></a>
                                    
                                    @if ($transaction->status == 'pending')
                                        <a href="{{ route('transacciones.edit', $transaction) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('messages.transactions_list.action_edit') }}"><i class="fas fa-edit"></i></a>
                                    @endif

                                    @if (in_array($transaction->status, ['pending', 'accepted']))
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="{{ __('messages.transactions_list.action_cancel') }}" onclick="confirmCancel('{{ route('transacciones.cancel', $transaction) }}')">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">{{ __('messages.transactions_list.no_transactions') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Formulario oculto que se usará para enviar la cancelación --}}
<form id="cancel-form" action="" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="reason" id="cancellation-reason">
</form>
@endsection

@push('scripts')
<style>
    /* Estilos para la paginación de DataTables */
    .dataTables_paginate .page-item {
        margin: 0 3px;
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- CORRECCIÓN ---
        // Se define el objeto de traducción según el idioma actual de la aplicación
        const languageConfig = "{{ app()->getLocale() }}" === 'es' ? {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sSearch":         "Buscar:",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        } : {}; // Si no es español, se usa el inglés por defecto de DataTables

        $('#transactionsTable').DataTable({
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                   "<'row'<'col-sm-12'tr>>" +
                   "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": languageConfig,
            "order": [[ 5, "desc" ]]
        });
    });

// --- FUNCIÓN DE CANCELACIÓN ACTUALIZADA CON AJAX (FETCH) ---
    function confirmCancel(actionUrl) {
        Swal.fire({
            title: "{{ __('messages.alerts.confirm_cancel_title') }}",
            text: "{{ __('messages.alerts.confirm_cancel_text') }}",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: "{{ __('messages.alerts.confirm_cancel_button') }}",
            cancelButtonText: "{{ __('messages.alerts.cancel_button') }}"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "{{ __('messages.alerts.cancellation_reason_title') }}",
                    input: 'textarea',
                    inputPlaceholder: "{{ __('messages.alerts.cancellation_reason_placeholder') }}",
                    showCancelButton: true,
                    confirmButtonText: "{{ __('messages.alerts.cancellation_reason_confirm') }}",
                    cancelButtonText: "{{ __('messages.alerts.cancel_button') }}",
                    inputValidator: (value) => {
                        if (!value) {
                            return "{{ __('messages.alerts.cancellation_reason_error') }}"
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed && result.value) {
                        const form = document.getElementById('cancel-form');
                        const reasonInput = document.getElementById('cancellation-reason');
                        reasonInput.value = result.value;
                        form.action = actionUrl;
                        form.submit();
                    }
                });
            }
        });
    }
</script>
@endpush