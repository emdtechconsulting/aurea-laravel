@extends('layout')
@section('title', 'Inicio')
@section('mainClass', 'inicio')
@section('content')
<div class="contenedorInicio">
 <img src="{{ asset('logo-aurea.png') }}" alt="AUREA" class="logoInicio">
 <p class="subtitulo">Sanguchitos y más</p>
 @foreach([
 ['aurea.nuevo','basket','Nuevo Pedido','Registrar un nuevo pedido'],
 ['aurea.pedidos','list','Pedidos','Consultar pedidos registrados'],
 ['aurea.productos','box','Productos','Catálogo y precios'],
 ['aurea.resumen','chart','Resumen del día','Consultar pedidos e importes']
 ] as [$route,$icon,$title,$description])
 <a href="{{ route($route) }}" class="menuCard">
  <span class="iconoMenu" aria-hidden="true">@include('aurea.icon', ['icon' => $icon])</span>
  <span><span class="tituloMenu">{{ $title }}</span><span class="descripcionMenu">{{ $description }}</span></span>
 </a>
 @endforeach
 <form method="post" action="/logout" class="homeLogout">@csrf<button class="btnOutline" type="submit">Cerrar sesión</button></form>
</div>
@endsection
