<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Equipo;

class JugadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categorias = array("Rey Master", "Super Master", "Master", "Super Senior", "Senior", "Super Junior", "Junior");
        $names = array( "Antonio", "Isaias", "Fabian", "Jesus", "Angel",
                        "Freddy", "Justin", "Josue", "Tobias", "Adrian",
                        "Jose", "Marco", "Pablo", "Carlos", "Mateo", "Edgar");

        foreach(range(1,15) as $index){
            $equipo = Equipo::orderByRaw("RAND()")->where('estado', 1)->first();
            shuffle($names);
            $name = $names[0]." ".$names[1];
            shuffle($categorias);
            $categoria = $categorias[0];
            DB::table('jugadors')->insert([
                'nombres' => $name,
                'apellidos' => strtoupper(str_random(15)),
                'edad' => rand(18, 40),
                'identificacion' => (string)rand(1000000000, 9999999999),
                'rol' => strtolower(str_random(10)),
                'email' => strtolower(str_random(15)).'@gmail.com',
                'telefono' => '09'.rand(),
                'peso' => mt_rand(5500, 10000)/100,
                'categoria' => $categoria,
                'num_camiseta' => rand(1, 99),
                'id_equipo' => $equipo->id,
                'estado' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }

    }
}
