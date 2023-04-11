<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table('users')->truncate();
        
        DB::table('users')->insert([
            'nombre' => 'JABlack Soft',
            'email' => 'info@jablacksoft.com',
            'password' => bcrypt('j4black'),
            'id_uType' => '1',
        ]);

        DB::table('users')->insert([
            'nombre' => 'General',
            'id_uType' => '2 ',
        ]);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
