<?php

use Illuminate\Database\Seeder;

class UserLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\UserLevel::class, 3)->create();
    }
}
