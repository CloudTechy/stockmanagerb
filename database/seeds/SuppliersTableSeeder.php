<?php

use App\BankDetail;
use App\Supplier;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Supplier::class, 20)->create()->each(function ($supplier) {

            factory(BankDetail::class)->create(['supplier_id' => $supplier->id]);

        });

    }
}
