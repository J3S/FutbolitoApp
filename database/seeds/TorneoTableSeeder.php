<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Categoria;

class TorneoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach(range(2014,2016) as $year){
            $categorias = Categoria::all();
            foreach($categorias as $categoria){
                DB::table('torneos')->insert([
                    'id_categoria' => $categoria->id,
                    'anio' => $year,
                    'estado' => 1,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);
            }  
        }
    }
}
