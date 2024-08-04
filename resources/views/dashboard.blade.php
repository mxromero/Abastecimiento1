@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Bienvenido a la Plataforma de Salsa & Ketchup</b></h5>
                        <p class="card-text">
                            Nuestra nueva plataforma de productores le permitirá gestionar las notas de recepción que se realizaron en Aconcagua Foods.
                            {{ $ExpSAP }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info">{{ $ExpSAP }}</span>
                    <div class="info-box-content">
                    <span class="info-box-text"><b>SAP</b></span>
                    <span class="info-box-text">No Exportadas</span>
                    </div>
                </div>
            </div>             

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h1>Ordenes Cargadas</h1>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Orden Prev.</th>
                                    <th>Versión Fab.</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($OPCargadas as $item)
                                    <tr>
                                        <td>{{ $item->material_orden }}</td>
                                        <td>{{ $item->NOrdPrev }}</td>
                                        <td>{{ $item->VersionF }}</td>
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
