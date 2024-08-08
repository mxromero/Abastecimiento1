@extends('adminlte::page')

@section('title', 'Producción')

@if ($errors->has('uma'))
    <div class="alert alert-danger">
        {{ $errors->first('uma') }}
    </div>
@endif

@section('content')
<div class="container">
    <h1>Editar Producción</h1>

    @if ($data->isNotEmpty())
        @foreach ($data as $produccion)
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#basica{{ $produccion->uma }}">Información Básica</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#produccion{{ $produccion->uma }}">Detalles de Producción</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#adicional{{ $produccion->uma }}">Información Adicional SAP</a>
                </li>
            </ul>
            <form action="{{ route('produccion.update', $produccion->uma) }}" method="POST" id="editForm{{ $produccion->uma }}">
                <div class="tab-content">
                    <div id="basica{{ $produccion->uma }}" class="tab-pane fade show active">

                        @csrf
                        @method('PATCH')

                        <div class="form-group">
                            <label for="uma">UMA:</label>
                            <input type="text" name="uma" id="uma" class="form-control" value="{{ $produccion->uma }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="material">Material:</label>
                            <input type="text" name="material" id="material" class="form-control" value="{{ $produccion->material }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="lote">Lote:</label>
                            <input type="text" name="lote" id="lote" class="form-control" value="{{ $produccion->lote }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="almacen">Almacén:</label>
                            <input type="text" name="almacen" id="almacen" class="form-control" value="{{ $produccion->almacen }}" readonly>
                        </div>

                        <button type="submit" class="btn btn-primary">Actualizar Producción</button>
                        <button type="button" class="btn btn-secondary" onclick="goBack()">Volver</button>

                    </div>

                <div id="produccion{{ $produccion->uma }}" class="tab-pane fade">
                    <div class="form-group">
                        <label for="NOrdPrev">Orden Prev.:</label>
                        <input type="text" name="NOrdPrev" id="NOrdPrev" class="form-control" value="{{ $produccion->NOrdPrev }}">
                    </div>

                    <div class="form-group">
                        <label for="VersionF">Versión Fab.:</label>
                        <input type="text" name="VersionF" id="VersionF" class="form-control" value="{{ $produccion->VersionF }}">
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" value="{{ $produccion->fecha }}">
                    </div>

                    <div class="form-group">
                        <label for="hora">Hora:</label>
                        <input type="time" name="hora" id="hora" class="form-control" value="{{ $produccion->hora }}">
                    </div>

                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="text" name="cantidad" id="cantidad" class="form-control" value="{{ $produccion->cantidad }}">
                    </div>

                    <div class="form-group">
                        <label for="paletizadora">Línea:</label>
                        <input type="text" name="paletizadora" id="paletizadora" class="form-control" value="{{ $produccion->paletizadora }}">
                    </div>

                    <div class="form-group">
                        <label for="batch1">Batch:</label>
                        <input type="text" name="batch1" id="batch1" class="form-control" value="{{ trim($produccion->batch1) }}">
                    </div>
                </div>

                <div id="adicional{{ $produccion->uma }}" class="tab-pane fade">
                    <div class="form-group">
                        <label for="exp_sap">EXP SAP:</label>
                        <input type="text" name="exp_sap" id="exp_sap" class="form-control" value="{{ trim($produccion->exp_sap) }}">
                    </div>

                    <div class="form-group">
                        <label for="n_doc">N° Doc:</label>
                        <input type="text" name="n_doc" id="n_doc" class="form-control" value="{{ $produccion->n_doc }}">
                    </div>

                </div>
            </div>
        </form>
        @endforeach
    @else
        <p>No se encontraron producciones.</p>
    @endif
</div>
<script>
    function goBack() {
        window.history.back();
    }

        // Agregar evento submit al formulario
        $('form[id^="editForm"]').on('submit', function(event) {
            // Prevenir el envío predeterminado del formulario
            event.preventDefault();

            // Obtener todos los inputs del formulario
            let formData = $(this).serialize();

            // Enviar la solicitud AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'PATCH',
                data: formData,
                success: function(response) {
                    // Manejar la respuesta exitosa (puedes mostrar un mensaje, redirigir, etc.)
                    alert('Producción actualizada con éxito');
                },
                error: function(error) {
                    // Manejar errores (mostrar mensajes de error, etc.)
                    console.error(error);
                }
            });
        });
    </script>
@endsection

