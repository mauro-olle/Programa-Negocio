<?php

use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('product_categories')->truncate();
        
        DB::table('product_categories')->insert([
            'nombre' => 'Varios',
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
