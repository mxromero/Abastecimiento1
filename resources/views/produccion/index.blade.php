@extends('adminlte::page')

@section('title', 'Dashboard')



@section('content')
<div class="container">
    <form action="{{ route('produccion.buscar') }}" method="GET" class="form-inline">
        @csrf
        <div class="form-group">
            <label for="fecha_inicial">Fecha Inicial</label>
            <input type="date" id="fecha_inicial" name="fecha_inicial" class="form-control" value="{{ old('fecha_inicial', \Carbon\Carbon::now()->format('Y-m-d')) }}">
        </div>
        <span>(*)</span>
        <div class="form-group">
            <label for="fecha_final">Fecha Fin</label>
            <input type="date" id="fecha_final" name="fecha_final" class="form-control" value="{{ old('fecha_final', \Carbon\Carbon::now()->addDays(5)->format('Y-m-d')) }}">
        </div>
        <span>(*)</span>
        <div class="form-group">
            <label for="paletizadora">Elija Paletizadora</label>
            <select id="paletizadora" name="paletizadora" class="form-control">
                @foreach($paletizadoras as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="material">Elija Material</label>
            <select id="material" name="material" class="form-control">
                @foreach($materiales as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success">Ver</button>
    </form>

        </div>
@endsection
