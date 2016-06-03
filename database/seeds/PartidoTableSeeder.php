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
    {/*
        $names = array( "Antonio", "Isaias", "Fabian", "Jesus", "Angel",
                "Freddy", "Justin", "Josue", "Tobias", "Adrian",
                "Jose", "Marco", "Pablo", "Carlos", "Mateo", "Edgar");

        foreach(range(1,30) as $index){
            shuffle($names);
            $name = $names[0];
            $year = rand(2008, 2016);
            $month = rand(1, 3);
            $day = rand(1, 28);
            $hour = rand(8, 16);
            $date = Carbon::create($year, $month, $day, $hour, 0, 0);
            $torneo = Torneo::orderByRaw("RAND()")->get();
            $torneoEquipo = TorneoEquipo::where('id_torneo', $torneo->id);
           // $torneoEquipoRand = $torneoEquipo::orderByRaw("RAND()")->get();
           // $equipoLocal = Equipo::where('estado', 1)
           //    ->where('id', $torneoEquipoRand->id);

            DB::table('partidos')->insert([
                'lugar' => strtoupper(str_random(10)),
                'arbitro' => $name,
                'fecha' => $date->format('Y-m-d H:i:s'),
                'observacion' => str_random(50),
                'gol_visitante' => rand(0,4),
                'gol_local' => rand(0,4),
                'id_torneo' => $torneo->id,
                'estado' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }
    */
    }
}
