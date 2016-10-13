<?php

use Illuminate\Database\Seeder;

class ActivationTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('activation_types')->insert([
            'name'=>'PC',
            'description'=>'Package Code'
        ]);
        
        DB::table('activation_types')->insert([
            'name'=>'FS',
            'description'=>'Free Start'
        ]);
        
        DB::table('activation_types')->insert([
            'name'=>'CD',
            'description'=>'Commission Deduction'
        ]);
    }
}
