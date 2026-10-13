<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class AureaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $path = storage_path('framework/testing/views');
        File::ensureDirectoryExists($path);
        config(['view.compiled' => $path]);
    }

    public function test_new_pages_are_private_and_render_with_existing_data(): void
    {
        foreach (['/nuevo-pedido', '/pedidos', '/productos', '/resumen'] as $path) {
            $this->get($path)->assertRedirect('/login');
        }
        $this->actingAs(User::factory()->create());
        $product = Product::create(['name' => 'Producto prueba', 'price_cents' => 250, 'active' => true]);
        $this->postJson('/api/v1/orders', $this->payload($product->id))->assertCreated();
        $this->get('/nuevo-pedido')->assertOk()->assertSee('Producto prueba');
        $this->get('/productos')->assertOk()->assertSee('Producto prueba');
        $this->get('/pedidos')->assertOk()->assertSee('Cliente prueba');
        $this->get('/resumen')->assertOk()->assertSee('S/ 5.00');
    }

    private function payload(int $id): array
    {
        return ['client_request_id' => (string) Str::uuid(), 'customer_name' => 'Cliente prueba', 'delivery_type' => 'recojo', 'items' => [['product_id' => $id, 'quantity' => 2]]];
    }

    public function test_private_api_requires_authentication(): void
    {
        $this->getJson('/api/v1/orders')->assertUnauthorized();
        $this->get('/')->assertRedirect('/login');
    }

    public function test_server_prices_order_and_retries_do_not_duplicate(): void
    {
        $this->actingAs(User::factory()->create());
        $p = Product::create(['name' => 'Petipán', 'price_cents' => 250, 'active' => true]);
        $d = $this->payload($p->id);
        $d['total_cents'] = 1;
        $this->postJson('/api/v1/orders', $d)->assertCreated()->assertJsonPath('total_cents', 500);
        $this->postJson('/api/v1/orders', $d)->assertCreated();
        $this->assertDatabaseCount('orders', 1);
        $d['customer_name'] = 'Otro';
        $this->postJson('/api/v1/orders', $d)->assertConflict();
    }

    public function test_inactive_product_and_missing_delivery_address_are_rejected(): void
    {
        $this->actingAs(User::factory()->create());
        $p = Product::create(['name' => 'Triple', 'price_cents' => 300, 'active' => false]);
        $d = $this->payload($p->id);
        $this->postJson('/api/v1/orders', $d)->assertUnprocessable();
        $this->assertDatabaseCount('orders', 0);
        $d['delivery_type'] = 'delivery';
        $this->postJson('/api/v1/orders', $d)->assertJsonValidationErrors('address');
    }

    public function test_final_status_cannot_reopen_and_price_snapshot_is_preserved(): void
    {
        $this->actingAs(User::factory()->create());
        $p = Product::create(['name' => 'Triple', 'price_cents' => 300, 'active' => true]);
        $response = $this->postJson('/api/v1/orders', $this->payload($p->id))->assertCreated();
        $id = $response->json('id');
        $p->update(['price_cents' => 999]);
        $this->assertEquals(300, Order::find($id)->items->first()->unit_price_cents);
        $this->patchJson("/api/v1/orders/$id/status", ['status' => 'entregado'])->assertOk();
        $this->patchJson("/api/v1/orders/$id/status", ['status' => 'pendiente'])->assertConflict();
    }

    public function test_mobile_token_login_and_revocation(): void
    {
        $u = User::factory()->create(['password' => bcrypt('PasswordParaPrueba!')]);
        $token = $this->postJson('/api/v1/mobile/token', ['email' => $u->email, 'password' => 'PasswordParaPrueba!', 'device_name' => 'Android'])->assertOk()->json('token');
        $this->withToken($token)->getJson('/api/v1/products')->assertOk();
        $this->withToken($token)->deleteJson('/api/v1/mobile/token')->assertNoContent();
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_panel_and_login_render(): void
    {
        $this->get('/login')->assertOk();
        $this->actingAs(User::factory()->create())->get('/')->assertOk()->assertSee('AUREA');
    }
}
