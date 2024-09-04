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


<div class="container">
    <form action="{{ route('reclasificado.create') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="unidadManipulacion">Unidad Manipulación</label>
            <input type="text" name="unidadManipulacion" id="unidadManipulacion" class="form-control" value="{{ old('unidadManipulacion') }}">
            @error('unidadManipulacion')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="form-group">
            <button type="submit" name="enviar" id="enviar" class="btn btn-success">Procesar</button>
        </div>
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
