@extends('adminlte::page')

@section('title', 'Crear Estamento')

@section('content_header')
    <h1>{{ __('Crear Estamento') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Nuevo Rol</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.store') }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
