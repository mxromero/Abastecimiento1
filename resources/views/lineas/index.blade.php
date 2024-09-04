@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Crea Nueva Línea</h1>
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <div class="container">
        <form action="{{ route('lineas.create') }}" method="POST" class="mt-4">
            @csrf

            <div class="form-group">
                <label for="paletizadora">Paletizadora:</label>
                <input type="text" name="paletizadora" id="paletizadora" class="form-control" value="{{ old('paletizadora') }}">
                @error('paletizadora')
                 <div class="text-danger">{{ $message }}</div>
                 @enderror
            </div>

            <button type="submit" id="crear" class="btn btn-primary">Agregar</button>
        </form>
    </div>
@stop
