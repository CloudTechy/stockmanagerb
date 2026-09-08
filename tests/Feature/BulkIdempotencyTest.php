<?php

namespace Tests\Feature;

use App\Attribute;
use App\AttributeProduct;
use App\Customer;
use App\Order;
use App\OrderDetail;
use App\Product;
use App\Purchase;
use App\PurchaseDetail;
use App\Supplier;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BulkIdempotencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_bulk_sales_duplicate_retry_does_not_double_decrement_stock()
    {
        [$user, $attributeProduct, $order] = $this->makeSalesFixture();

        $payload = [
            'order_id' => $order->id,
            'orderDetails' => [
                [$attributeProduct->id => '2 100'],
            ],
        ];

        $first = $this->actingAs($user)->postJson('/api/orderdetails', $payload, ['Idempotency-Key' => 'sale-bulk-1']);
        $first->assertStatus(200);

        $second = $this->actingAs($user)->postJson('/api/orderdetails', $payload, ['Idempotency-Key' => 'sale-bulk-1']);
        $second->assertStatus(200);

        $this->assertSame(8, AttributeProduct::find($attributeProduct->id)->available_stock);
        $this->assertSame(1, OrderDetail::where('order_id', $order->id)->count());
    }

    public function test_bulk_purchases_duplicate_retry_does_not_double_increment_stock()
    {
        [$user, $purchase, $attributeProduct] = $this->makePurchaseFixture();

        $payload = [
            'purchaseDetails' => [
                [
                    'purchase_id' => $purchase->id,
                    'product' => 'Milk',
                    'brand' => 'BrandX',
                    'quantity' => 5,
                    'price' => 30,
                    'sale_price' => 45,
                    'percent_sale' => 0,
                    'pku' => 'pcs',
                    'size' => 'M',
                    'category' => 'Food',
                ],
            ],
        ];

        $first = $this->actingAs($user)->postJson('/api/purchasedetails', $payload, ['Idempotency-Key' => 'purchase-bulk-1']);
        $first->assertStatus(200);

        $second = $this->actingAs($user)->postJson('/api/purchasedetails', $payload, ['Idempotency-Key' => 'purchase-bulk-1']);
        $second->assertStatus(200);

        $this->assertSame(15, AttributeProduct::find($attributeProduct->id)->available_stock);
        $this->assertSame(1, PurchaseDetail::where('purchase_id', $purchase->id)->count());
    }

    public function test_same_idempotency_key_with_different_payload_is_rejected()
    {
        [$user, $attributeProduct, $order] = $this->makeSalesFixture();

        $firstPayload = [
            'order_id' => $order->id,
            'orderDetails' => [
                [$attributeProduct->id => '2 100'],
            ],
        ];
        $secondPayload = [
            'order_id' => $order->id,
            'orderDetails' => [
                [$attributeProduct->id => '3 100'],
            ],
        ];

        $this->actingAs($user)->postJson('/api/orderdetails', $firstPayload, ['Idempotency-Key' => 'sale-bulk-2'])->assertStatus(200);
        $this->actingAs($user)->postJson('/api/orderdetails', $secondPayload, ['Idempotency-Key' => 'sale-bulk-2'])->assertStatus(409);

        $this->assertSame(8, AttributeProduct::find($attributeProduct->id)->available_stock);
    }

    public function test_in_flight_duplicate_key_returns_processing_conflict_without_stock_mutation()
    {
        [$user, $attributeProduct, $order] = $this->makeSalesFixture();

        $payload = [
            'order_id' => $order->id,
            'orderDetails' => [
                [$attributeProduct->id => '2 100'],
            ],
        ];

        DB::table('idempotency_requests')->insert([
            'user_id' => $user->id,
            'scope' => 'orderdetails.store',
            'idempotency_key' => 'sale-bulk-inflight',
            'request_hash' => hash('sha256', json_encode(Arr::sortRecursive($payload))),
            'status' => 'processing',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($user)
            ->postJson('/api/orderdetails', $payload, ['Idempotency-Key' => 'sale-bulk-inflight'])
            ->assertStatus(409);

        $this->assertSame(10, AttributeProduct::find($attributeProduct->id)->available_stock);
        $this->assertSame(0, OrderDetail::where('order_id', $order->id)->count());
    }

    private function makeSalesFixture(): array
    {
        $this->seedSharedLookups();
        $user = $this->makeUser();
        $supplier = Supplier::create([
            'name' => 'Supplier A',
            'number' => '111111',
            'email' => 'suppliera@example.com',
            'user_id' => $user->id,
        ]);
        $customer = Customer::create([
            'name' => 'Customer A',
            'number' => '222222',
            'email' => 'customera@example.com',
            'user_id' => $user->id,
            'owing' => 0,
        ]);
        $product = Product::create([
            'name' => 'Milk',
            'supplier_id' => $supplier->id,
            'category' => 'Food',
            'pku' => 'pcs',
            'user_id' => $user->id,
            'discount' => 0,
        ]);
        $attribute = Attribute::create([
            'type' => 'BrandX',
            'description' => 'Brand',
        ]);
        $attributeProduct = AttributeProduct::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'size' => 'M',
            'purchase_price' => 20,
            'sale_price' => 100,
            'percent_sale' => 0,
            'available_stock' => 10,
            'user_id' => $user->id,
        ]);
        $order = Order::create([
            'customer_id' => $customer->id,
            'user_id' => $user->id,
            'staff' => $user->username,
            'customer_name' => $customer->name,
        ]);

        return [$user, $attributeProduct, $order];
    }

    private function makePurchaseFixture(): array
    {
        $this->seedSharedLookups();
        $user = $this->makeUser();
        $supplier = Supplier::create([
            'name' => 'Supplier B',
            'number' => '333333',
            'email' => 'supplierb@example.com',
            'user_id' => $user->id,
        ]);
        $purchase = Purchase::create([
            'supplier_id' => $supplier->id,
            'supplier_name' => $supplier->name,
            'staff' => $user->username,
            'user_id' => $user->id,
        ]);
        $product = Product::create([
            'name' => 'Milk',
            'supplier_id' => $supplier->id,
            'category' => 'Food',
            'pku' => 'pcs',
            'user_id' => $user->id,
            'discount' => 0,
        ]);
        $attribute = Attribute::create([
            'type' => 'BrandX',
            'description' => 'Brand',
        ]);
        $attributeProduct = AttributeProduct::create([
            'product_id' => $product->id,
            'attribute_id' => $attribute->id,
            'size' => 'M',
            'purchase_price' => 20,
            'sale_price' => 40,
            'percent_sale' => 0,
            'available_stock' => 10,
            'user_id' => $user->id,
        ]);

        return [$user, $purchase, $attributeProduct];
    }

    private function seedSharedLookups(): void
    {
        DB::table('user_levels')->insert([
            'id' => 1,
            'name' => 'administrator',
            'role' => 'administrator',
        ]);
        DB::table('units')->insert(['name' => 'pcs']);
        DB::table('categories')->insert(['name' => 'Food', 'description' => 'Food category']);
        DB::table('sizes')->insert(['name' => 'M', 'description' => 'Medium']);
    }

    private function makeUser(): User
    {
        return User::create([
            'username' => 'tester',
            'first_name' => 'Test',
            'last_name' => 'User',
            'number' => '999999',
            'email' => 'tester@example.com',
            'address' => 'Addr',
            'user_level_id' => 1,
            'password' => Hash::make('password'),
            'activated' => true,
        ]);
    }
}
