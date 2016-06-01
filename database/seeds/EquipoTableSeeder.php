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
        $equipos = array("39A", "40B", "41C", "44C", "27A", "31C", "50B", "51C", "29B", 
            "33A", "37B", "25A");
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
