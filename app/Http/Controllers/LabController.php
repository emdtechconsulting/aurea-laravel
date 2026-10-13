<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\CreateOrder;
use Illuminate\Http\Request;

class LabController extends Controller
{
    public function panel()
    {
        return view('aurea.inicio');
    }

    public function newOrderPage()
    {
        return view('aurea.nuevo-pedido', ['products' => Product::where('active', true)->orderBy('name')->get()]);
    }

    public function ordersPage()
    {
        return view('aurea.pedidos', ['orders' => Order::with('items')->latest()->paginate(30)]);
    }

    public function productsPage()
    {
        return view('aurea.productos', ['products' => Product::orderBy('name')->get()]);
    }

    public function summaryPage()
    {
        return view('aurea.resumen', ['summary' => $this->summary()]);
    }

    public function products()
    {
        return Product::orderBy('name')->paginate(100);
    }

    public function saveProduct(Request $r, ?Product $product = null)
    {
        $d = $r->validate(['name' => 'required|string|max:120', 'price_cents' => 'required|integer|min:1|max:1000000', 'active' => 'required|boolean']);
        $product ??= new Product;
        $product->fill($d)->save();

        return response()->json($product, $product->wasRecentlyCreated ? 201 : 200);
    }

    public function orders()
    {
        return Order::with('items')->latest()->paginate(30);
    }

    public function storeOrder(StoreOrderRequest $r, CreateOrder $service)
    {
        return response()->json($service->handle($r->validated()), 201);
    }

    public function status(Request $r, Order $order)
    {
        $d = $r->validate(['status' => 'required|in:pendiente,preparando,entregado,cancelado']);
        abort_if(in_array($order->status, ['entregado', 'cancelado']) && $order->status !== $d['status'], 409, 'Un pedido finalizado no puede cambiar.');
        $order->update($d);

        return $order;
    }

    public function summary()
    {
        $q = Order::whereDate('created_at', now()->toDateString());

        return ['date' => now()->toDateString(), 'orders' => (clone $q)->count(), 'total_registered_cents' => (clone $q)->where('status', '!=', 'cancelado')->sum('total_cents'), 'delivered_cents' => (clone $q)->where('status', 'entregado')->sum('total_cents')];
    }
}
