<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrder
{
    public function handle(array $data): Order
    {
        $hash = hash('sha256', json_encode($data));
        try {
            return DB::transaction(function () use ($data, $hash) {
                $existing = Order::where('client_request_id', $data['client_request_id'])->first();
                if ($existing) {
                    abort_unless(hash_equals($existing->request_hash, $hash), 409, 'Este identificador ya se utilizó con otro pedido.');

                    return $existing->load('items');
                }
                $items = [];
                $total = 0;
                foreach ($data['items'] as $item) {
                    $product = Product::whereKey($item['product_id'])->lockForUpdate()->first();
                    if (! $product || ! $product->active) {
                        throw ValidationException::withMessages(['items' => 'Producto no disponible.']);
                    }
                    $subtotal = $product->price_cents * $item['quantity'];
                    $total += $subtotal;
                    $items[] = ['product_id' => $product->id, 'product_name' => $product->name, 'quantity' => $item['quantity'], 'unit_price_cents' => $product->price_cents, 'subtotal_cents' => $subtotal];
                }
                $order = Order::create([...collect($data)->except('items')->all(), 'request_hash' => $hash, 'total_cents' => $total]);
                $order->items()->createMany($items);

                return $order->load('items');
            });
        } catch (UniqueConstraintViolationException $e) {
            $existing = Order::where('client_request_id', $data['client_request_id'])->first();
            if (! $existing) {
                throw $e;
            }
            abort_unless(hash_equals($existing->request_hash, $hash), 409, 'Este identificador ya se utilizó con otro pedido.');

            return $existing->load('items');
        }
    }
}
