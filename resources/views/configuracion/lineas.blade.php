@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Configuración Líneas</h1>
@stop

@section('content')

    <table id="tablaLineas" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Paletizadora</th>
                <th>Orden Prev.</th>
                <th>Version Fab.</th>
                <th>Almacén</th>
                <th>Material Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>

@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>

    <script>
    $(document).ready(function() {

        let uri = '{{  route('configuracion.historial') }}';

        $('#tablaLineas').DataTable({
            processing: true,
            serverSide: true,
            ajax: uri, // Ruta para obtener los datos vía AJAX
            columns: [
                { data: 'paletizadora' },
                { data: 'NOrdPrev' },
                { data: 'VersionF' },
                { data: 'almacen' },
                { data: 'material_orden' },
                { data: 'acciones', orderable: false, searchable: false } // Configura la columna de acciones
            ]
        });
    });
    </script>
@stop
