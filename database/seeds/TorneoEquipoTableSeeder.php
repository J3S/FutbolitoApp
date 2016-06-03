<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Torneo;
use App\Equipo;

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
    	
    	foreach($equipos as $equipo){
			$torneo = Torneo::orderByRaw("RAND()")->where('estado', 1)->first();
	        DB::table('torneo_equipos')->insert([
	                'id_torneo' => $torneo->id,
	                'id_equipo' => $equipo->id,
	                'created_at' => Carbon::now()->toDateTimeString(),
	                'updated_at' => Carbon::now()->toDateTimeString()
	        ]);
    	}
    }
}
