<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsuarioTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuarios')->insert([
            'user' => 'admin',
            'pass' => bcrypt('secret'),
            'nombre' => 'José Estrada',
            'estado' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
        DB::table('usuarios')->insert([
            'user' => 'digitador1',
            'pass' => bcrypt('secret'),
            'nombre' => 'Julio Carrasco',
            'estado' => '1',
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString()
        ]);
    }
}
