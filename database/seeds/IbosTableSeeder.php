<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class IbosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('ibos')->insert([
            'id'=>900000001,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>0,
            'placement_id'=>0,
            'placement_position'=>'',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000002,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000001,
            'placement_id'=>900000001,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000003,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000001,
            'placement_id'=>900000001,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000004,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000002,
            'placement_id'=>900000002,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000005,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000002,
            'placement_id'=>900000002,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000006,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000003,
            'placement_id'=>900000003,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000007,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000003,
            'placement_id'=>900000003,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000008,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000004,
            'placement_id'=>900000004,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000009,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000004,
            'placement_id'=>900000004,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000010,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000005,
            'placement_id'=>900000005,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000011,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000005,
            'placement_id'=>900000005,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000012,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000006,
            'placement_id'=>900000006,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000013,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000006,
            'placement_id'=>900000006,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000014,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000007,
            'placement_id'=>900000007,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000015,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000007,
            'placement_id'=>900000007,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000016,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000008,
            'placement_id'=>900000008,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000017,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000008,
            'placement_id'=>900000008,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000018,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000009,
            'placement_id'=>900000009,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000019,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000009,
            'placement_id'=>900000009,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000020,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000010,
            'placement_id'=>900000010,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000021,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000010,
            'placement_id'=>900000010,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000022,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000011,
            'placement_id'=>900000011,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000023,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000011,
            'placement_id'=>900000011,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000024,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000012,
            'placement_id'=>900000012,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000025,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000012,
            'placement_id'=>900000012,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000026,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000013,
            'placement_id'=>900000013,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000027,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000013,
            'placement_id'=>900000013,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000028,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000014,
            'placement_id'=>900000014,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000029,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000014,
            'placement_id'=>900000014,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000030,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000015,
            'placement_id'=>900000015,
            'placement_position'=>'L',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        DB::table('ibos')->insert([
            'id'=>900000031,
            'firstname'=>'Michael',
            'middlename'=>'',
            'lastname'=>'Javier',
            'is_part_company'=>true,
            'sponsor_id'=>900000015,
            'placement_id'=>900000015,
            'placement_position'=>'R',
            'total_purchase_amount'=>0,
            'ranking_lions_id'=>0,
            'is_admin'=>false,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
