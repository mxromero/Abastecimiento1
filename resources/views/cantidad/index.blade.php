@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Agregar Cantidad Material</h1>
@stop

@section('content')

<!-- Mensaje de éxito -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Mensajes de error -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
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
            "ordering": true
        });
    });
</script>
@stop
