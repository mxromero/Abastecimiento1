@extends('adminlte::page')

@section('title', 'Estamento')

@section('content_header')
    <h1>{{ __('Roles') }}</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lista de Roles</h3>
                        <div class="card-tools">
                            <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm">Crear Rol</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="rolesTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
@stop

@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {

            let uri = '{{  route('role.show') }}';
            $('#rolesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: uri,
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'acciones', name: 'acciones', orderable: false, searchable: false }
                ]
            });
        });
    </script>
@stop
