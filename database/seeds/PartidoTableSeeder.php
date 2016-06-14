<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Equipo;
use App\Torneo;
use App\TorneoEquipo;

class PartidoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $torneos = Torneo::where('estado', 1)->get();
        $names = array( "Antonio", "Isaias", "Fabian", "Jesus", "Angel",
                "Freddy", "Justin", "Josue", "Tobias", "Adrian",
                "Jose", "Marco", "Pablo", "Carlos", "Mateo", "Edgar");

        foreach ($torneos as $torneo) {
            foreach (range(1, 12) as $jornada) {
                foreach (range(1, 4) as $partidosJornada) {
                    shuffle($names);
                    $name = $names[0];
                    $year = $torneo->anio;
                    $month = $jornada;
                    $day = rand(1, 28);
                    $hour = rand(8, 16);
                    $date = Carbon::create($year, $month, $day, $hour, 0, 0);
                    $torneoEquipo = TorneoEquipo::orderByRaw("RAND()")->where('id_torneo', $torneo->id)->first();
                    $equipoL = Equipo::orderByRaw("RAND()")->where('estado', 1)->where('id', $torneoEquipo->id_equipo)->first();
                    do {
                        $torneoEquipo2 = TorneoEquipo::orderByRaw("RAND()")->where('id_torneo', $torneo->id)->first();
                        $equipoV = Equipo::orderByRaw("RAND()")->where('estado', 1)->where('id', $torneoEquipo2->id_equipo)->first();
                    } while($equipoV->id == $equipoL->id);
                    DB::table('partidos')->insert([
                        'lugar' => strtoupper(str_random(10)),
                        'arbitro' => $name,
                        'fecha' => $date->format('Y-m-d H:i:s'),
                        'observacion' => str_random(50),
                        'gol_visitante' => rand(0, 4),
                        'gol_local' => rand(0, 4),
                        'jornada' => $jornada,
                        'equipo_local' => $equipoL->nombre,
                        'equipo_visitante' => $equipoV->nombre,
                        'id_torneo' => $torneo->id,
                        'estado' => 1,
                        'created_at' => Carbon::now()->toDateTimeString(),
                        'updated_at' => Carbon::now()->toDateTimeString()
                    ]); 
                }

            }
        }
    }
}
