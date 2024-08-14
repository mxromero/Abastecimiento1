@extends('adminlte::page')

@section('title', 'Notificación Producción')


@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">
@endpush

@section('content')

    @if (session('success'))
    <div class="alert alert-success" id="success-alert">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger" id="danger-alert">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="container">
        <form action="{{ route('produccion.creacion') }}" method="POST" name="crea_produccion" id="notificacion">
            @csrf

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="cod_linea">Cod Linea:</label>
                        <select id="cod_linea" name="cod_linea" class="form-control">
                            @foreach ($Lineas as $linea)
                                <option value="{{ $linea->paletizadora }}">{{ $linea->paletizadora }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div id="campos-editables" style="display: none;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="cant_a">Cant. A:</label>
                            <input type="text" id="cant_a" name="cant_a" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="batch_a">Batch. A:</label>
                            <input type="text" id="batch_a" name="batch_a" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="fecha">Fecha</label>
                            <input type="date" id="fecha" name="fecha" value="{{ date('Y-m-d') }}" class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="hora"></label>
                            <input type="time" name="hora" id="hora" value="{{ date('H:m:s') }}" class="form-control">
                        </div>
                    </div>
                </div>
            </div>

            <div id="datos-adicionales" style="display: none;">
                <hr>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Material:</label>
                            <input type="text" name="material" id="material" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Lote:</label>
                            <input type="text" name="lote" id="lote" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Orden Previsional:</label>
                            <input type="text" name="orden_prv" id="orden_prv" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Versión Fabricación:</label>
                            <input type="text" name="version_f" id="version_f" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Almacén:</label>
                            <input type="text" name="almacen" id="almacen" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Unidad Manipulación a Notificar:</label>
                            <input type="text" name="uma" id="uma" class="form-control" readonly>
                        </div>
                    </div>

            </div>

            <button type="submit" class="btn btn-primary">Procesar</button>

        </form>

            <button type="reset" class="btn btn-info" onclick="location.reload()">Limpiar</button>
    </div>

@endsection


@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
@endpush


@section('js')

<script>
    var obtenerDatosUrl = "{{ route('produccion.obtenerDatos') }}";

    $(document).ready(function() {
        $('#cod_linea').on('change', function() {
            var codLinea = $(this).val();

            $.ajax({
                url: obtenerDatosUrl,
                method: 'GET',
                data: {
                    cod_linea: codLinea,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Actualizar los campos editables
                    $('#cant_a').val(response.cajas);
                    $('#batch_a').val(response.batch1);

                    // Actualizar los campos de solo lectura
                    $('#material').val(response.material_orden);
                    $('#lote').val(response.lote);
                    $('#orden_prv').val(response.NOrdPrev);
                    $('#version_f').val(response.VersionF);
                    $('#uma').val(response.uma + 1);
                    $('#almacen').val(response.almacen);


                    // Mostrar los datos adicionales y los campos editables
                    $('#datos-adicionales').show();
                    $('#campos-editables').show();

                    $('#cant_a').focus();

                },
                error: function(error) {
                    Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'Error al obtener los datos. Por favor, inténtalo de nuevo.'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                location.reload();
                            }
                        });
                }
            });
        });
    });
</script>
@endsection
