<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class JugadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = array( "Antonio", "Isaias", "Fabian", "Jesus", "Angel",
                        "Freddy", "Justin", "Josue", "Tobias", "Adrian",
                        "Jose", "Marco", "Pablo", "Carlos", "Mateo", "Edgar");
        
        foreach(range(1,30) as $index){
            shuffle($names);
            $name = $names[0].$names[1];
            DB::table('jugadors')->insert([
                'nombres' => $name,
                'apellidos' => strtoupper(str_random(15)),
                'edad' => rand(18, 40),
                'identificacion' => (string)rand(1000000000, 9999999999),
                'rol' => strtolower(str_random(10)),
                'email' => strtolower(str_random(10)).'@gmail.com',
                'telefono' => '09'.rand(),
                'peso' => mt_rand(2000, 15000)/100,
                'num_camiseta' => rand(0, 99),
                'estado' => true,
                'puedeJugar' => true,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]);
        }
       
    }
}
