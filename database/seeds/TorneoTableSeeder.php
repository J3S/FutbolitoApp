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

        $categorias = Categoria::all();
        foreach($categorias as $categoria){
            DB::table('torneos')->insert([
                'id_categoria' => $categoria->id,
                'anio' => 2016,
                'estado' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }
        
    }
}
