<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $t) {
            $t->id();
            $t->string('name', 120);
            $t->unsignedInteger('price_cents');
            $t->boolean('active')->default(true);
            $t->timestamps();
        });
        Schema::create('orders', function (Blueprint $t) {
            $t->id();
            $t->uuid('client_request_id')->unique();
            $t->string('request_hash', 64);
            $t->string('customer_name', 120);
            $t->string('phone', 30)->nullable();
            $t->string('delivery_type', 20);
            $t->string('address', 250)->nullable();
            $t->string('status', 20)->default('pendiente');
            $t->unsignedInteger('total_cents');
            $t->timestamps();
        });
        Schema::create('order_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('order_id')->constrained()->cascadeOnDelete();
            $t->foreignId('product_id')->constrained()->restrictOnDelete();
            $t->string('product_name');
            $t->unsignedInteger('quantity');
            $t->unsignedInteger('unit_price_cents');
            $t->unsignedInteger('subtotal_cents');
        });
        Schema::create('personal_access_tokens', function (Blueprint $t) {
            $t->id();
            $t->morphs('tokenable');
            $t->text('name');
            $t->string('token', 64)->unique();
            $t->text('abilities')->nullable();
            $t->timestamp('last_used_at')->nullable();
            $t->timestamp('expires_at')->nullable()->index();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['personal_access_tokens', 'order_items', 'orders', 'products'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
