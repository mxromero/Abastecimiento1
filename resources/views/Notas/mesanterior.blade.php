@extends('adminlte::page')

@section('title', 'Nota Recepciones')

@section('content_header')
    <i class="fa fa-table fa-fw"></i> {{ __('Recepciones') }}<span> > <b>{{ $mesActual }}</b></span></h1><br>
    <i class="fas fa-truck"></i> {{ __('Total Kilos : ') }}{{ $totalKilos }}
@stop

@section('content')

    <table id="pdfTable" class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>Entrega</th>
                <th>Lote</th>
                <th>Fecha</th>
                <th>Guía</th>
                <th>Productor</th>
                <th>Especie</th>
                <th>Variedad</th>
                <th>Kilos</th>
                <th>Bins</th>
                <th>Descarga</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    @push('css')
        <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
    @endpush

    @push('js')
        <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            let datos = @json($estructuraCarpetas);
            
            // Aplanar el array de datos
            let datosAplastados = [];
            for (let anio in datos) {
                for (let mes in datos[anio]) {
                    datosAplastados = datosAplastados.concat(datos[anio][mes]);
                }
            }

            $('#pdfTable').DataTable({
                processing: true,
                serverSide: false,
                data: datosAplastados, // Usar los datos aplanados
                columns: [
                    { data: 'Entrega', orderable: true },
                    { data: 'Lote', orderable: true },
                    { data: 'Fecha', orderable: true },
                    { data: 'Guía', orderable: true },
                    { data: 'Productor', orderable: false },
                    { data: 'Especie', orderable: true },
                    { data: 'Variedad', orderable: true },
                    { data: 'Kilos', orderable: false },
                    { data: 'Bins', orderable: false },
                    { 
                        data: 'Descarga', 
                        orderable: false,
                        render: function (data, type, row) {
                            return '<a href="' + data + '" download="' + row.filename + '">Descargar</a>';
                        }
                    }
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

            $('#pdfTable_length').hide(); // Ocultar selector de cantidad de filas
        });
    </script>
  @endpush
@endsection