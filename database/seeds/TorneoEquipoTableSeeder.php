<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Torneo;
use App\Equipo;
use App\Categoria;

class TorneoEquipoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$equipos = Equipo::all();
        $torneos = Torneo::all();
    	$categorias = Categoria::all();

    	foreach($equipos as $equipo){
            foreach($torneos as $torneo){
                $categoria = Categoria::find($torneo->id_categoria);
                if($equipo->categoria == $categoria->nombre){
                    DB::table('torneo_equipos')->insert([
                        'id_torneo' => $torneo->id,
                        'id_equipo' => $equipo->id,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]);
                }
            }
    	}
    }
}
