@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Agregar Cantidad Material</h1>
@stop

@section('content')

<!-- Mensaje de éxito -->
@if (session('success'))
    <x-alert type="success" id="cantidad-success-alert">
        {{ session('success') }}
    </x-alert>
@endif

<!-- Mensajes de error -->
@if ($errors->any())
    <x-alert type="danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </x-alert>
@endif

<div class="container">
    <div class="row">
        <!-- Columna para el formulario -->
        <div class="col-md-6">
            <form action="{{ route('cantidad.store') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="Material">Material</label>
                    <select name="material" id="material" class="form-control">
                        <option value="">Seleccione un material</option>
                        @foreach($materiales as $material)
                            <option value="{{ $material }}">{{ $material }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="cantidad">Cantidad</label>
                    <input type="number" name="cantidad" id="cantidad" class="form-control" value="{{ old('cantidad') }}">
                </div>
                <button type="submit" class="btn btn-primary">Agregar</button>
            </form>
        </div>

        <!-- Columna para el grid -->
        <div class="col-md-6">
            <table id="materiales-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($resultado as $material => $cantidad)
                    <tr>
                        <td>{{ $material }}</td>
                        <td>{{ $cantidad }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@stop

@section('js')
<script>

    $(document).ready(function() {
        $('#materiales-table').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            language: {
                    "sProcessing":    "Procesando...",
                    "sLengthMenu":    "Mostrar _MENU_ registros",
                    "sZeroRecords":   "No se encontraron resultados",
                    "sEmptyTable":    "Ningún dato disponible en esta tabla",
                    "sInfo":          "Registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    "sInfoEmpty":     "Registros del 0 al 0 de un total de 0 registros",
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
@stop
