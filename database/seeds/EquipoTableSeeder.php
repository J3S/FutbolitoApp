<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EquipoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $letras = range('A', 'D');
        $promocion = range('20', '50');
        $equipos = array();
        foreach($promocion as $promo){
            foreach($letras as $letra){
                $nombre = $promo.$letra;
                $equipos[] = $nombre;
            }
        }

        $names = array( "Antonio", "Isaias", "Fabian", "Jesus", "Angel",
                        "Freddy", "Justin", "Josue", "Tobias", "Adrian",
                        "Jose", "Marco", "Pablo", "Carlos", "Mateo", "Edgar");

        foreach($equipos as $equipo){
            shuffle($names);
            $name = $names[0];
            DB::table('equipos')->insert([
                'nombre' => $equipo,
                'director_tecnico' => $name,
                'estado' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }
        
    }
}
