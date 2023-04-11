<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(UserTypeSeeder::class);
        $this->call(OrderTypeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(FormaPagoSeeder::class);
        //$this->call(ProductCategorySeeder::class);
    }
}
