@extends('layout')
@section('title', 'Resumen del día')
@section('content')
<div class="pageTitle"><h1>Resumen del día</h1><a class="btnOutline" href="/">Inicio</a></div>
<p class="listCaption">{{ \Carbon\Carbon::parse($summary['date'])->format('d/m/Y') }} · Hora de Lima · Importes en soles</p>
<div class="metricsGrid">
 <section class="card metricCard"><span class="metricIcon" aria-hidden="true">@include('aurea.icon',['icon'=>'list'])</span><h2>Pedidos de hoy</h2><strong class="metricValue">{{ $summary['orders'] }}</strong><p class="muted">Incluye todos los estados.</p></section>
 <section class="card metricCard"><span class="metricIcon" aria-hidden="true">@include('aurea.icon',['icon'=>'basket'])</span><h2>Importe registrado</h2><strong class="metricValue">S/ {{ number_format($summary['total_registered_cents'] / 100, 2) }}</strong><p class="muted">Excluye los pedidos cancelados.</p></section>
 <section class="card metricCard"><span class="metricIcon" aria-hidden="true">@include('aurea.icon',['icon'=>'chart'])</span><h2>Importe entregado</h2><strong class="metricValue">S/ {{ number_format($summary['delivered_cents'] / 100, 2) }}</strong><p class="muted">Pedidos registrados hoy que figuran como entregados.</p></section>
</div>
<section class="card"><h2>Seguimiento de tus pedidos</h2><p class="muted">Los importes registrados y entregados no representan pagos verificados.</p><a href="/pedidos" class="btnPrimary summaryLink">Consultar Pedidos</a></section>
@endsection
