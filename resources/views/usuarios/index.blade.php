@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1>{{ __('Usuarios') }}</h1>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <table id="usuariosTable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>RUT</th>
                                    <th>Nombre Proveedor</th>
                                    <th>Email</th>
                                    <th>Fecha de Creación</th>
                                    <th>Fecha de Actualización</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($usuarios as $usuario)
                                    <tr>
                                        <td>{{ $usuario->id }}</td>
                                        <td>{{ $usuario->rut }}</td>
                                        <td>{{ $usuario->name }}</td>
                                        <td>{{ $usuario->email }}</td>
                                        <td>{{ $usuario->created_at->format('d-m-Y') }}</td>
                                        <td>{{ $usuario->updated_at->format('d-m-Y')}}</td>
                                        <td>
                                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-primary btn-sm">Editar</a>
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        $(document).ready(function() {
            $('#usuariosTable').DataTable({
                "language": {
                    "url": "https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json" // Idioma en español
                }
            });
        });
    </script>
@stop