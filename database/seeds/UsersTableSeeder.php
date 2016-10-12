<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name'=>'Michael Javier',
            'email'=>'michaeljavier@gmail.com',
            'password'=>bcrypt('123456'),
            'role'=>'admin',
            'ibo_id'=>900000001
        ]);
    }
}
