<?php

use Illuminate\Database\Seeder;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            array('name' => 'Product1', 'price'=>'10'), 
            array('name' => 'Product2', 'price'=>'10.5'),
            array('name' => 'Product3', 'price'=>'11'),
            array('name' => 'Product4', 'price'=>'12'),
            array('name' => 'Product5', 'price'=>'9'),
        ]);
        
        DB::table('discounts')->insert([
            array('discount' => '10'), 
            array('discount' => '15'), 
            array('discount' => '20'), 
            array('discount' => '25')
        ]);
        
        $this->command->info('Data was added!');
    }
}
