@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><b>Bienvenido a la Plataforma de Productores</b></h5>
                        <p class="card-text">
                            Nuestra nueva plataforma de productores le permitirá gestionar las notas de recepción que se realizaron en Aconcagua Foods.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><b>Nota Recepciones</b> - Mes Actual</h5>
                    </div>
                    <div class="card-body">
                       <p class="card-text">
                            Dentro de la opción "Nota Recepciones" se encuentra el sub-menú "Recepciones" en el podrá revisar las recepciones del mes actual.
                        </p>
                        <img src="{{ asset('images/mesActual.png') }}" class="img-fluid" alt="Imagen 1">
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title"><b>Nota Recepciones</b> - Mes Anterior</h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Dentro de la opción "Rec.Mes Anterior" se encuentra el sub-menú "Recepciones" en el podrá revisar las recepciones del mes anterior.
                        </p><br>
                        <img src="{{ asset('images/mesAnterior.png') }}" class="img-fluid" alt="Imagen 2">
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@stop
