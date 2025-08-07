@extends('layouts.app')

@section('title', __('messages.admin_transactions_list.page_title'))
@section('page-title', __('messages.admin_transactions_list.header'))

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table id="adminTransactionsTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>{{ __('messages.table.id') }}</th>
                        <th>{{ __('messages.table.title') }}</th>
                        <th>{{ __('messages.table.amount') }}</th>
                        <th>{{ __('messages.admin_transactions_list.participants') }}</th>
                        <th>{{ __('messages.table.status') }}</th>
                        <th>{{ __('messages.table.date') }}</th>
                        <th>{{ __('messages.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $transaction)
                        <tr>
                            <td class="fw-bold">#TR-{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $transaction->title }}</td>
                            <td>${{ number_format($transaction->amount, 2) }}</td>
                            <td>
                                @foreach($transaction->participants as $participant)
                                    <span class="badge bg-secondary">{{ $participant->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                <span class="badge bg-info-subtle text-info-emphasis rounded-pill">{{ __('messages.statuses.' . $transaction->status) }}</span>
                            </td>
                            <td>{{ $transaction->created_at->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary">{{ __('messages.admin_transactions_list.view_details_button') }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
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
        } : {};

        $('#adminTransactionsTable').DataTable({
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            "language": languageConfig,
            "order": [[ 5, "desc" ]]
        });
    });
</script>
@endpush