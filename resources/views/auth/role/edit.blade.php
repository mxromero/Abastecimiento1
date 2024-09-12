@extends('adminlte::page')

@section('title', 'Editar Rol')

@section('content_header')
    <h1>{{ __('Editar Rol') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Editar Rol</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('role.update', $role->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Nombre:</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $role->name) }}" required>
                            </div>

                            <button type="submit" class="btn btn-primary">Actualizar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
