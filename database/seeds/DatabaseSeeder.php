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
        $this->call(IbosTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(CommissionsTableSeeder::class);
        $this->call(ActivationTypeTableSeeder::class);
    }
}
