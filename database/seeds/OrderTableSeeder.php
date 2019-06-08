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

        factory(App\Order::class, 10)->create()->each(function ($order) {

            factory(App\OrderDetail::class, mt_rand(1, 5))->create(['order_id' => $order->id]);

            $total_price = Order::find($order->id)->amount;

            factory(Transaction::class)->create(['amount' => $total_price, 'payment' => $total_price, 'invoice_id' => function () use ($order) {

                return factory(Invoice::class)->create(['type' => 'order', 'order_id' => $order->id])->id;
            }]);

        });
    }
}
