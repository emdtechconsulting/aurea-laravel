<!doctype html>
<html lang="es">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <meta name="theme-color" content="#6A3D1F">
 <title>@yield('title', 'AUREA') · Sanguchitos y más</title>
 <link rel="icon" type="image/png" href="{{ asset('logo-aurea.png') }}">
 <link rel="stylesheet" href="{{ asset('css/aurea.css') }}?v=1.1">
 <script src="{{ asset('js/aurea.js') }}?v=1.1" defer></script>
</head>
<body class="@yield('bodyClass')">
 <a class="skipLink" href="#contenido">Ir al contenido</a>
 @auth
 @unless(request()->is('/'))
 <header class="headerAurea">
  <a href="{{ route('aurea.inicio') }}" class="brandLink"><img src="{{ asset('logo-aurea.png') }}" alt="AUREA" class="logoPrincipal"><span>Sanguchitos y más</span></a>
  <form method="post" action="/logout">@csrf<button class="btnOutline" type="submit">Salir</button></form>
 </header>
 <nav class="navAurea" aria-label="Navegación principal">
  @foreach([['aurea.inicio','Inicio'],['aurea.nuevo','Nuevo pedido'],['aurea.pedidos','Pedidos'],['aurea.productos','Productos'],['aurea.resumen','Resumen']] as [$route,$text])
   <a href="{{ route($route) }}" @class(['navItem', 'active' => request()->routeIs($route)]) @if(request()->routeIs($route)) aria-current="page" @endif>{{ $text }}</a>
  @endforeach
 </nav>
 @endunless
 @endauth
 <main id="contenido" class="@yield('mainClass', 'pagina')">
  <div id="message" class="message" role="status" aria-live="polite" hidden></div>
  @yield('content')
 </main>
 <footer class="appFooter">AUREA · Sanguchitos y más</footer>
 @stack('scripts')
</body>
</html>
