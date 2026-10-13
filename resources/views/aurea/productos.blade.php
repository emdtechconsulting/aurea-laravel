@extends('layout')
@section('title', 'Productos')
@section('content')
<div class="pageTitle"><h1>Productos</h1><a class="btnOutline" href="/">Inicio</a></div>
<div class="catalogLayout">
<section class="card"><h2 id="productFormTitle">Registrar Producto</h2><form id="productForm">
 <label for="editProduct">Producto nuevo o existente</label><select id="editProduct"><option value="">Nuevo producto</option>@foreach($products as $product)<option value="{{ $product->id }}">{{ $product->name }}</option>@endforeach</select>
 <label for="productName">Nombre</label><input id="productName" maxlength="120" required>
 <label for="productPrice">Precio por unidad en soles</label><input id="productPrice" type="number" min="0.01" max="10000" step="0.01" required inputmode="decimal">
 <label for="productActive">Disponibilidad</label><select id="productActive"><option value="1">Activo</option><option value="0">Inactivo</option></select>
 <button class="guardar" type="submit" id="saveProduct">Guardar Producto</button>
</form></section>
<section class="card"><h2>Catálogo</h2><div class="catalogList">
 @forelse($products as $product)<article class="catalogItem"><div><h3>{{ $product->name }}</h3><span @class(['badge', 'badgeActive' => $product->active, 'badgeInactive' => !$product->active])>{{ $product->active ? 'Activo' : 'Inactivo' }}</span></div><div class="catalogPrice"><strong>S/ {{ number_format($product->price_cents / 100, 2) }}</strong><button type="button" class="btnOutline" data-edit-product="{{ $product->id }}">Editar</button></div></article>
 @empty<p class="emptyState">Registra el primer producto de AUREA.</p>@endforelse
</div></section>
</div>
@endsection
@push('scripts')<script type="application/json" id="productsData">{!! json_encode($products->map->only(['id','name','price_cents','active'])->values(), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) !!}</script>@endpush
