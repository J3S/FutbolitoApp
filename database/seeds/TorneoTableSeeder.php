<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TorneoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = array("RM", "SM", "M", "SS", "S", "SJ", "J");

        foreach($categorias as $categoria){
            $year = rand(2008, 2016);
            $month = rand(1, 3);
            $day = rand(1, 28);
            $date = Carbon::create($year, $month, $day, 0, 0, 0);
            DB::table('torneos')->insert([
                'categoria' => $categoria,
                'fecha_inicio' => $date->format('Y-m-d H:i:s'),
                'fecha_fin' => $date->addYear(1)->format('Y-m-d H:i:s'),
                'estado' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

    }
}
