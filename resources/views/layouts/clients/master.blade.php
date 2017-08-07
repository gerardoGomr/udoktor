@extends('layouts.master')

@section('menu')
    <ul class="list">
        <li class="header">MENÚ</li>
        <li class="active">
            <a href="/clientes">
                <i class="material-icons">dashboard</i>
                <span>Resumen</span>
            </a>
        </li>
        <li>
            <a href="/clientes/servicios">
                <i class="material-icons">local_hospital</i>
                <span>Buscar servicios</span>
            </a>
        </li>
        <li>
            <a href="/clientes/citas">
                <i class="material-icons">date_range</i>
                <span>Mis citas</span>
            </a>
        </li>
    </ul>
@stop