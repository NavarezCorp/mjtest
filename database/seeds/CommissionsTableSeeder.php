<?php

use Illuminate\Database\Seeder;

class CommissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('commissions')->insert([
            'name'=>'Direct Sponsor Commission',
            'description'=>'Direct Sponsor Commission',
            'amount'=>200
        ]);
        
        DB::table('commissions')->insert([
            'name'=>'Indirect Sponsor Commission',
            'description'=>'Indirect Sponsor Commission',
            'amount'=>15
        ]);
        
        DB::table('commissions')->insert([
            'name'=>'Matching Bonus',
            'description'=>'Matching Bonus',
            'amount'=>200
        ]);
    }
}
