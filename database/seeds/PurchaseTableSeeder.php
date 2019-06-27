<?php

use App\Attribute;
use App\AttributeProduct;
use App\Invoice;
use App\Product;
use App\Purchase;
use App\PurchaseDetail;
use App\Transaction;
use Illuminate\Database\Seeder;

class PurchaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {

            factory(Purchase::class, 10)->create()->each(function ($purchase) {

                factory(PurchaseDetail::class, 1)->create(['purchase_id' => $purchase->id])->each(function ($purchaseDetail) use ($purchase) {

                    factory(Product::class, 1)->create(['supplier_id' => $purchase->supplier_id, 'name' => $purchaseDetail->product, 'pku' => $purchaseDetail->pku, 'category' => $purchaseDetail->category])->each(function ($product) use ($purchaseDetail) {

                        $attribute_id = Attribute::where('type', $purchaseDetail->brand)->first()->id;

                        factory(AttributeProduct::class, 1)->create(['product_id' => $product->id, 'attribute_id' => $attribute_id, 'purchase_price' => $purchaseDetail->price, 'sale_price' => $purchaseDetail->sale_price, 'percent_sale' => $purchaseDetail->percent_sale, "available_stock" => $purchaseDetail->quantity, 'size' => $purchaseDetail->size]);
                    });

                });

                $purchase = Purchase::find($purchase->id);

                factory(Transaction::class)->create(['cost' => $purchase->amount, 'amount' => $purchase->amount, 'payment' => 0, 'invoice_id' => function () use ($purchase) {

                    return factory(Invoice::class)->create(['cost' => $purchase->amount, 'amount' => $purchase->amount, 'type' => 'purchase', 'purchase_id' => $purchase->id])->id;
                }]);

            });

            // Commit Transaction
            DB::commit();
        } catch (\Exception $e) {
            //Rollback Transaction
            DB::rollback();
            echo "error!!! operation not successful <br>" . $e->getMessage();
            exit;
        }

    }
}
