@extends('layout')
@section('title', 'Ingresar')
@section('mainClass', 'loginWrap')
@section('content')
<div class="contenedorInicio loginContainer">
 <img src="{{ asset('logo-aurea.png') }}" alt="AUREA" class="logoInicio">
 <p class="subtitulo">Sanguchitos y más</p>
 <section class="card loginCard">
  <h1>Ingresar</h1><p class="muted">Accede para gestionar tus pedidos.</p>
  @if($errors->any())<p class="formError" role="alert">{{ $errors->first() }}</p>@endif
  <form method="post" action="/login">@csrf
   <label for="email">Correo electrónico</label><input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username">
   <label for="password">Contraseña</label><input id="password" name="password" type="password" required autocomplete="current-password">
   <button class="guardar" type="submit">Ingresar</button>
  </form>
 </section>
</div>
@endsection
