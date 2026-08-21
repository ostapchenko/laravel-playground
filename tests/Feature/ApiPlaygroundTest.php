<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

uses(LazilyRefreshDatabase::class);

test('api responses are json even without an accept header', function () {
    $this->get('/api/customers')
        ->assertUnauthorized()
        ->assertHeader('content-type', 'application/json');
});

test('users can create tokens and fetch the authenticated user', function () {
    $user = User::factory()->create([
        'email' => 'interview@example.com',
        'password' => 'password',
    ]);

    $loginResponse = $this->post('/api/login', [
        'email' => 'interview@example.com',
        'password' => 'password',
        'device_name' => 'interview-client',
    ])->assertSuccessful();

    $token = $loginResponse->json('token');

    $this->withToken($token)
        ->getJson('/api/me')
        ->assertSuccessful()
        ->assertJsonPath('data.id', $user->id)
        ->assertJsonPath('data.email', 'interview@example.com');
});

test('authenticated users can manage basic commerce resources', function () {
    $token = User::factory()->create()->createToken('test-client')->plainTextToken;

    $customerId = $this->withToken($token)
        ->postJson('/api/customers', [
            'name' => 'Ada Lovelace',
            'email' => 'ada@example.com',
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.email', 'ada@example.com')
        ->json('data.id');

    $productId = $this->withToken($token)
        ->postJson('/api/products', [
            'name' => 'Interview Keyboard',
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Interview Keyboard')
        ->json('data.id');

    $orderId = $this->withToken($token)
        ->postJson('/api/orders', [
            'customer_id' => $customerId,
            'status' => 'paid',
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.customer_id', $customerId)
        ->assertJsonPath('data.status', 'paid')
        ->json('data.id');

    $orderItemId = $this->withToken($token)
        ->postJson('/api/order-items', [
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => 2,
            'unit_price' => 99.99,
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.quantity', 2)
        ->assertJsonPath('data.product.id', $productId)
        ->json('data.id');

    $this->withToken($token)
        ->patchJson("/api/customers/{$customerId}", [
            'name' => 'Ada Byron',
        ])
        ->assertSuccessful()
        ->assertJsonPath('data.name', 'Ada Byron');

    $this->withToken($token)
        ->deleteJson("/api/order-items/{$orderItemId}")
        ->assertNoContent();

    expect(Customer::find($customerId))->not->toBeNull()
        ->and(Product::find($productId))->not->toBeNull();
});
