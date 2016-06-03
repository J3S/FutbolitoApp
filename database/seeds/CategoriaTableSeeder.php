<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class CategoriaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$categorias = array("Rey Master", "Super Master", "Master", "Super Senior", "Senior", "Super Junior", "Junior");

		foreach($categorias as $categoria){
		    DB::table('categorias')->insert([
		        'nombre' => $categoria,
		        'created_at' => Carbon::now()->toDateTimeString(),
		        'updated_at' => Carbon::now()->toDateTimeString()
		    ]);
		}
        
    }
}
