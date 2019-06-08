<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(SizeTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(UnitTableSeeder::class);
        $this->call(AttributeTableSeeder::class);
        $this->call(StatusTableSeeder::class);
        $this->call(BankTableSeeder::class);
        $this->call(UserLevelTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(SuppliersTableSeeder::class);
        $this->call(CustomerTableSeeder::class);
        $this->call(TypeTableSeeder::class);
        $this->call(AnnouncementTableSeeder::class);
        $this->call(PurchaseTableSeeder::class);
        $this->call(OrderTableSeeder::class);

    }
}
