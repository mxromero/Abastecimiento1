@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Producción</h1>
@stop


@section('content')

    @if (session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" id="danger-alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table id="produccionTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Material</th>
                <th>Lote</th>
                <th>UMA</th>
                <th>Cantidad</th>
                <th>Batch</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Paletizadora</th>
                <th>NordPrev</th>
                <th>VersionF</th>
                <th>Acción</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@stop

@push('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@push('js')
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>

            window.onload = function() {
                            let successAlert = document.getElementById('success-alert');
                            if (successAlert) {
                                setTimeout(function() {
                                    successAlert.style.display = 'none';
                                }, 5000);
                            }

                            let errorAlert = document.getElementById('danger-alert');
                            if(errorAlert) {
                                setTimeout(function() {
                                    errorAlert.style.display = 'none';
                                }, 5000);
                            }
                        };

        function confirmarEliminacion(event, uma) {
            alert('uma' + uma);
            event.preventDefault(); // Prevenir el envío del formulario

            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText:
        'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed)
        {
                    // Enviar el formulario si el usuario confirma
                    $(event.target).closest('form').submit();
                }
            });
        }

        $(document).ready(function() {

            $('#produccionTable').DataTable({
                data: @json($data),
                columns: [
                    { data: 'material', name: 'material' },
                    { data: 'lote', name: 'lote' },
                    { data: 'uma', name: 'uma' },
                    { data: 'cantidad', name: 'cantidad' },
                    { data: 'batch1', name: 'batch1' },
                    { data: 'fecha', name: 'fecha'},
                    { data: 'hora', name: 'hora' },
                    { data: 'paletizadora', name: 'paletizadora' },
                    { data: 'NordPrev', name: 'NordPrev' },
                    { data: 'VersionF', name: 'VersionF' },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ],
            dom: '<"top"lf>rt<"bottom"ip><"clear">',
            language: {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "Ningún dato disponible en esta tabla",
                    "sInfo":          "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Mostrando registros del 0 al 0 de un total de 0 registros",
                    "sInfoFiltered":  "(filtrado de un total de _MAX_ registros)",
                    "sInfoPostFix":   "",
                    "sSearch":        "Buscar:",
                    "sUrl":           "",
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
                    },
                    "buttons": {
                        "copyTitle": 'Copiar filas al portapapeles',
                        "copySuccess": {
                            _: '%d filas copiadas',
                            1: '1 fila copiada'
                        }
                    }
                }
            });
        });
    </script>
@endpush
