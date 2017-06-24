@extends('layouts.master')

@section('menu')
    <ul class="list">
        <li class="header">MENÚ</li>
        <li class="active">
            <a href="/prestador-servicios">
                <i class="material-icons">date_range</i>
                <span>Calendario</span>
            </a>
        </li>
        <li>
            <a href="/prestador-servicios/servicios">
                <i class="material-icons">local_hospital</i>
                <span>Mis servicios</span>
            </a>
        </li>
        <li>
            <a href="#" class="menu-toggle">
                <i class="material-icons">book</i>
                <span>Agenda</span>
            </a>

            <ul class="ml-menu">
                <li><a href="/prestador-servicios/agenda/configuracion">Configuración</a></li>
                <li><a href="/prestador-servicios/agenda/citas">Citas</a></li>
            </ul>
        </li>
    </ul>
@stop