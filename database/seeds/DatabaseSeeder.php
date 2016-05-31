<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // $this->call(UserTableSeeder::class);

        for ($i=0; $i < 30; $i++) {
            $this->call(JugadorTableSeeder::class);
        }

        Model::reguard();
    }
}
