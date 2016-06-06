<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Equipo;
use App\Categoria;

class JugadorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = array("ANTONIO", "ISAIAS", "FABIAN", "JESUS", "ANGEL","DANIEL","ALBERTO","IGNACIO","ISRAEL","PEDRO",
                        "FREDDY", "JUSTIN", "JOSUE", "TOBIAS", "ADRIAN","RAUL","EDISON","FAUSTO","ADRIANO",
                        "JOSE", "MARCO", "PABLO", "CARLOS", "MATEO", "EDGAR", "ROBERTO", "PATRICIO", "WILLIAM","BAUTISTA","SEBASTIAN","ANDRES","JORGE","ALEJANDRO","RAMIRO","DAVID","JULIO","JUAN","RAFAEL","GUILLERMO","JAIME");
        $apellidos = array('GARCIA','GONZALEZ','RODRIGUEZ','FERNANDEZ','LOPEZ','MARTINEZ','SANCHEZ','PEREZ','GOMEZ','MARTIN','JIMENEZ','RUIZ','HERNANDEZ','DIAZ','MORENO','MUÑOZ','ALVAREZ','ROMERO','ALONSO','GUTIERREZ','NAVARRO','TORRES','DOMINGUEZ','VAZQUEZ','RAMOS','RAMIREZ','SERRANO','BLANCO','MOLINA','MORALES','SUAREZ','ORTEGA','DELGADO','CASTRO','ORTIZ','RUBIO','MARIN','SANZ','NUÑEZ',"VACA","ACOSTA","CEDENO","LOOR","BRAVO","FLORES");
        $equipos = Equipo::all();

        foreach($equipos as $equipo){
            foreach(range(1,11) as $index){
            shuffle($names);
            shuffle($apellidos);
            $name = $names[0]." ".$names[1];
            $apellido = $apellidos[0]." ".$apellidos[1];
            DB::table('jugadors')->insert([
                    'nombres' => $name,
                    'apellidos' => $apellido,
                    'edad' => rand(18, 40),
                    'identificacion' => (string)rand(1000000000, 9999999999),
                    'rol' => strtolower(str_random(10)),
                    'email' => strtolower(str_random(15)).'@gmail.com',
                    'telefono' => '09'.rand(),
                    'peso' => mt_rand(5500, 10000)/100,
                    'categoria' => $equipo->categoria,
                    'num_camiseta' => rand(1, 23),
                    'id_equipo' => $equipo->id,
                    'estado' => true,
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString()
                ]);
            }
        }
    }
}
