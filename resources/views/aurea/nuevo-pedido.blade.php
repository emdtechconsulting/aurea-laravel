@extends('layout')
@section('title', 'Nuevo pedido')
@section('content')
<div class="pageTitle"><h1>Nuevo Pedido</h1><a class="btnOutline" href="/">Inicio</a></div>
<form id="orderForm">
 <section class="card"><h2>Datos del Cliente</h2>
  <div class="dosColumnas"><div><label for="customerName">Cliente</label><input id="customerName" name="customer_name" maxlength="120" required autocomplete="name"></div>
  <div><label for="phone">Teléfono</label><input id="phone" name="phone" maxlength="30" type="tel" autocomplete="tel" placeholder="Opcional"></div></div>
  <label for="address">Dirección</label><input id="address" name="address" maxlength="250" autocomplete="street-address" placeholder="Obligatoria para delivery">
 </section>
 <section class="card"><h2>Seleccionar Producto</h2><p class="muted">Elige un producto del catálogo.</p>
  <div class="gridProductos" id="productChoices">
   @forelse($products as $product)
   <button type="button" class="productoCard" data-product="{{ $product->id }}" aria-pressed="false"><span class="productoNombre">{{ $product->name }}</span><span class="productoDetalle">Precio por unidad</span><strong class="productoValor">S/ {{ number_format($product->price_cents / 100, 2) }}</strong></button>
   @empty<p class="emptyState">No hay productos activos. <a href="/productos">Registra un producto</a> para comenzar.</p>@endforelse
  </div>
 </section>
 <section class="card" id="quantitySection" hidden><h2>Seleccionar Cantidad</h2><p id="chosenName" class="chosenName"></p>
  <div class="quantityPresets" aria-label="Cantidades rápidas">@foreach([1,2,5,10] as $quantity)<button type="button" class="cantidadCard" data-quantity="{{ $quantity }}">{{ $quantity }}<small>{{ $quantity === 1 ? 'unidad' : 'unidades' }}</small></button>@endforeach</div>
  <div class="quantityControls"><div><label for="quantity">Cantidad</label><input id="quantity" type="number" min="1" max="100" value="1" step="1"></div><button type="button" id="addProduct" class="btnPrimary">Agregar al pedido</button></div>
 </section>
 <section class="card cardCarrito"><h2>Productos del Pedido</h2><div id="cart"><p class="carritoVacio">Todavía no has agregado productos.</p></div></section>
 <section class="card"><h2>Delivery</h2><p class="deliveryPregunta">¿Cómo se entregará el pedido?</p>
  <div class="deliveryOpciones"><label class="deliveryChoice"><input type="radio" name="delivery_type" value="recojo" checked><span>Recojo</span></label><label class="deliveryChoice"><input type="radio" name="delivery_type" value="delivery"><span>Delivery</span></label></div>
  <p class="muted">Esta versión registra la dirección; todavía no calcula un cargo de delivery.</p>
 </section>
 <section class="card cardResumen"><h2>Resumen del Pedido</h2><div class="filaResumen"><span>Productos</span><strong id="itemCount">0 unidades</strong></div><div class="filaTotal"><span>Total estimado</span><strong id="estimate">S/ 0.00</strong></div><p class="priceNote">Al guardar, el servidor confirma el total con los precios vigentes.</p></section>
 <div class="footerAcciones"><button id="submitOrder" type="submit" class="guardar" @disabled($products->isEmpty())>Guardar Pedido</button><button type="button" id="clearOrder" class="btnFooter limpiar">Limpiar formulario</button></div>
</form>
@endsection
@push('scripts')<script type="application/json" id="productsData">{!! json_encode($products->map->only(['id','name','price_cents'])->values(), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>@endpush
