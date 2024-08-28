@extends('adminlte::page')

@section('title', 'Producción')

@section('content_header')
    <h1>Configuración Líneas</h1>
@stop

@section('content')
    <div class="container">
        <form action="{{ route('configuracion.store') }}" method="POST" class="mt-4">
            @csrf

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
                <input type="text" name="material_orden" id="material_orden" class="form-control" value="{{ old('material_orden') }}">
                @error('material_orden')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="paletizadora">Paletizadora:</label>
                <select id="paletizadora" name="paletizadora" class="form-control">
                    @foreach ($Lineas as $linea)
                        <option value="{{ $linea }}">{{ $linea }}</option>
                    @endforeach
                </select>
                @error('paletizadora')
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

            <button type="submit" class="btn btn-primary">Crear</button>
        </form>
    </div>
    <script>
        document.getElementById('material_orden').addEventListener('blur', function() {

            let NOrdPrev = document.getElementById('NOrdPrev').value;
            let VersionF = document.getElementById('VersionF').value;
            let Material = document.getElementById('material_orden').value;
            let rutas = "{{ route('configuracion.valida_ordenes') }}";
            // Realizar solicitud AJAX a Laravel
            fetch(rutas, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')

                },
                // Puedes enviar datos adicionales si es necesario
                body: JSON.stringify({ 'OrdenPrev': NOrdPrev, 'Version': VersionF, 'Material' : Material})
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud');
                }
                return response.json();
            })
            .then(data => {
                // Manejar la respuesta de Laravel si es necesario
                console.log(data);
            })
            .catch(error => {
                // Manejar errores
                console.error('Error:', error);
            });
        });
    </script>
@stop
