@extends('adminlte::page')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0">Modificar Fichajes: {{ $user->name }}</h1>
        <a href="{{ url('eunomia/fichajes') }}" class="btn btn-default">Volver</a>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    <h3 class="text-muted">Desde aquí puede editar o eliminar fichajes del usuario.</h3>

                    {{-- Inserto el HTML generado por getFichajesUsuario --}}
                    {!! $fichajes_html !!}

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
