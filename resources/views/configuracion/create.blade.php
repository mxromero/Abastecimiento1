@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Configuración Líneas
        @if(isset($Lineas))
            {{ $Lineas[0] }}
        @endif
    </h1>
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
        <form action="{{ route('configuracion.update', ['id' => $Lineas[0] ]) }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="NOrdPrev">N° Ord. Prev.:</label>
                <input type="text" name="NOrdPrev" id="NOrdPrev" class="form-control" value="{{ old('NOrdPrev') }}">
                @error('NOrdPrev')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="VersionF">Versión F:</label>
                <input type="text" name="VersionF" id="VersionF" class="form-control" value="{{ old('VersionF') }}">
                @error('VersionF')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="material_orden">Material Orden:</label>
                <input type="text" name="material_orden" id="material_orden" class="form-control" value="{{ old('material_orden') }}" style="text-transform: uppercase;">
                @error('material_orden')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="almacen">Almacén:</label>
                <input type="text" name="almacen" id="almacen" class="form-control" value="{{ old('almacen') }}">
                @error('almacen')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="paletizadora">Paletizadora:</label>
                <select id="paletizadora" name="paletizadora" class="form-control">
                    @foreach ($Lineas as $linea)
                        <option value="{{ $linea }}" selected>{{ $linea }}</option>
                    @endforeach
                </select>
                @error('paletizadora')
                 <div class="text-danger">{{ $message }}</div>
                 @enderror
            </div>

            <button type="submit" id="crear" class="btn btn-primary">Actualizar</button>
            <button type="reset" id="crear" class="btn btn-success">Limpiar</button>
        </form>
    </div>
    <script>
        const materialOrdenInput = document.getElementById('material_orden');

            document.getElementById('NOrdPrev').focus();
            document.addEventListener('DOMContentLoaded', function() {

            if (materialOrdenInput) {
                materialOrdenInput.addEventListener('blur', function() {
                    let NOrdPrev = document.getElementById('NOrdPrev').value;
                    let VersionF = document.getElementById('VersionF').value;
                    let Material = document.getElementById('material_orden').value;
                    let rutas = "{{ route('configuracion.valida_ordenes') }}";

                    if (NOrdPrev.trim() === '' || VersionF.trim() === '' || Material.trim() === '') {
                                return; // Detener la ejecución si faltan campos
                        }

                    Swal.fire({
                        title: 'Validando datos en SAP',
                        allowOutsideClick: false, // Evitar que se cierre al hacer clic fuera
                        didOpen: () => {
                            Swal.showLoading(); // Mostrar la animación de carga
                                }
                    });


                    fetch(rutas, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ 'OrdenPrev': NOrdPrev, 'Version': VersionF, 'Material' : Material.toUpperCase() })
                    })
                    .then(response => response.json())
                    .then(data => {

                        Swal.close();

                        if (!data.success) {

                            document.getElementById('NOrdPrev').focus();
                            document.getElementById('crear').disabled = true;

                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message
                                });
                        } else {
                            document.getElementById('crear').disabled = false;
                            console.log(data.data); // Aquí puedes manejar los datos recibidos
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error en la validación',
                            text: 'Ocurrió un error al validar los datos. Por favor, inténtalo de nuevo.'
                        });
                    });
                });
            }
        });

    </script>
@stop
