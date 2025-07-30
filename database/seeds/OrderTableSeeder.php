<?php

use App\Invoice;
use App\Order;
use App\OrderDetail;
use App\Transaction;
use Illuminate\Database\Seeder;

class OrderTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {

        
        App\Order::class::factory()->count(10)->create()->each(function ($order) {

            factory(App\OrderDetail::class, mt_rand(1, 5))->create(['order_id' => $order->id]);

            $order = Order::find($order->id);

            factory(Transaction::class)->create(['cost' => $order->cost, 'amount' => $order->amount, 'payment' => 0, 'invoice_id' => function () use ($order) {

                return factory(Invoice::class)->create(['cost' => $order->cost, 'amount' => $order->amount, 'type' => 'order', 'order_id' => $order->id])->id;
            }]);

        });
    }
}
