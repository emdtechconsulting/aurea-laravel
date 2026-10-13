@extends('layout')
@section('title', 'Pedidos')
@section('content')
<div class="pageTitle"><h1>Pedidos</h1><a class="btnPrimary" href="/nuevo-pedido">Nuevo Pedido</a></div>
@if(request()->query('saved'))<p class="successNotice" role="status">El pedido se registró correctamente.</p>@endif
@if(request()->query('status'))<p class="successNotice" role="status">El estado del pedido se actualizó.</p>@endif
<p class="listCaption">{{ $orders->total() }} pedidos registrados · Página {{ $orders->currentPage() }} de {{ $orders->lastPage() }}</p>
<div class="ordersGrid">
@forelse($orders as $order)
<article class="pedidoCard" data-order-card="{{ $order->id }}">
 <div class="pedidoHeader"><span class="numeroPedido">Pedido #{{ $order->id }}</span><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></div>
 <h2 class="clientePedido">{{ $order->customer_name }}</h2>
 <div class="pedidoBody"><p>{{ $order->created_at->format('d/m/Y H:i') }}</p><p>{{ $order->delivery_type === 'delivery' ? 'Delivery' : 'Recojo' }}@if($order->phone) · {{ $order->phone }}@endif</p>@if($order->address)<p>{{ $order->address }}</p>@endif</div>
 <ul class="orderItems">@foreach($order->items as $item)<li><span>{{ $item->quantity }} × {{ $item->product_name }}</span><strong>S/ {{ number_format($item->subtotal_cents / 100, 2) }}</strong></li>@endforeach</ul>
 <div class="pedidoTotal"><span>Total</span><strong>S/ {{ number_format($order->total_cents / 100, 2) }}</strong></div>
 <label for="status-{{ $order->id }}">Estado del pedido</label><select id="status-{{ $order->id }}" data-order="{{ $order->id }}" data-current-status="{{ $order->status }}" @disabled(in_array($order->status,['entregado','cancelado']))>@foreach(['pendiente','preparando','entregado','cancelado'] as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>@endforeach</select>
 @if(in_array($order->status,['entregado','cancelado']))<small class="finalNote">Pedido finalizado.</small>@endif
</article>
@empty<section class="card emptyState"><h2>Aún no hay pedidos</h2><p>Registra tu primer pedido para comenzar.</p><a class="btnPrimary" href="/nuevo-pedido">Nuevo Pedido</a></section>@endforelse
</div>
<nav class="pagination" aria-label="Páginas de pedidos">@if($orders->previousPageUrl())<a class="btnOutline" href="{{ $orders->previousPageUrl() }}">Anterior</a>@endif @if($orders->hasMorePages())<a class="btnOutline" href="{{ $orders->nextPageUrl() }}">Siguiente</a>@endif</nav>
@endsection
