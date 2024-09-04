@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Reclasificado</h1>
@stop

@section('content')

<!-- Mensaje de éxito -->
@if (session('success'))
    <div id="success-message" class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<!-- Mensajes de error -->
@if ($errors->any())
    <div  id="error-message" class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


@section('content')
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" >

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<div class="container mt-5">
    <h2>Editar Producción</h2>
    <form action="{{ route('reclasificado.update', ['id' => $salida[0]['uma']]) }}" method="POST">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="uma">UMA</label>
            <input type="text" class="form-control" id="uma" name="uma" value="{{ $salida[0]['uma'] }}" >
        </div>
        <div class="form-group">
            <label for="material">Material</label>
            <input type="text" class="form-control" id="material" name="material" value="{{ $salida[0]['material'] }}">
        </div>
        <div class="form-group">
            <label for="lote">Lote</label>
            <input type="text" class="form-control" id="lote" name="lote" value="{{ $salida[0]['lote'] }}">
        </div>
        <div class="form-group">
            <label for="almacen">Almacén</label>
            <input type="text" class="form-control" id="almacen" name="almacen" value="{{ $salida[0]['almacen'] }}">
        </div>
        <div class="form-group">
            <label for="NordPrev">NordPrev</label>
            <input type="text" class="form-control" id="NordPrev" name="NordPrev" value="{{ $salida[0]['NordPrev'] }}">
        </div>
        <div class="form-group">
            <label for="VersionF">Versión F</label>
            <input type="text" class="form-control" id="VersionF" name="VersionF" value="{{ $salida[0]['VersionF'] }}">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="text" class="form-control" id="cantidad" name="cantidad" value="{{ $salida[0]['cantidad'] }}">
        </div>
        <div class="form-group">
            <label for="paletizadora">Paletizadora</label>
            <input type="text" class="form-control" id="paletizadora" name="paletizadora" value="{{ $salida[0]['paletizadora'] }}">
        </div>
        <div class="form-group">
            <label for="batch1">Batch</label>
            <input type="text" class="form-control" id="batch1" name="batch1" value="{{ $salida[0]['batch1'] }}">
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha" value="{{ @substr($salida[0]['fecha'],0,10) }}">
        </div>
        <div class="form-group">
            <label for="hora">Hora</label>
            <input type="text" class="form-control" id="hora" name="hora" value="{{ $salida[0]['hora'] }}">
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" value="{{ $salida[0]['descripcion'] }}">
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>

@section('js')
<script>
    // Ocultar el mensaje de éxito después de 5 segundos
    setTimeout(function() {
        let successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.style.display = 'none';
        }
    }, 5000); // 5000 milisegundos = 5 segundos

    // Ocultar el mensaje de error después de 5 segundos
    setTimeout(function() {
        let errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.style.display = 'none';
        }
    }, 5000); // 5000 milisegundos = 5 segundos
</script>
@stop


@stop

