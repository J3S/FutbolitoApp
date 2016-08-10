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

        $this->call(UsuarioTableSeeder::class);
        $this->call(CategoriaTableSeeder::class);
        $this->call(TorneoTableSeeder::class);
        $this->call(EquipoTableSeeder::class);
        $this->call(TorneoEquipoTableSeeder::class);
        $this->call(JugadorTableSeeder::class);
        $this->call(PartidoTableSeeder::class);
        $this->call(UsuarioTableSeeder::class);

        Model::reguard();
    }
}
