<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Usuario;

class LoginTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testLoginView1(){
        // Trata de acceder a un recurso sin loguearse
        $response = $this->call('GET', '/partido');
        $this->assertResponseStatus(302);
    }
    public function testLoginView2(){
        // Cargar directamente la vista login
        $response = $this->call('GET', '/');
        $this->assertResponseStatus(200);
    }
    public function testLoginOk()
    {
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'user' => 'admin',
            'password' => 'secret',
        ];
        $response = $this->call('POST', 'login', $parametros);
        $this->assertResponseStatus(302);
    }

    public function testLoginError1()
    {
        // Usuario incorrecto
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'user' => 'test',
            'password' => 'secret',
        ];
        $response = $this->call('POST', 'login', $parametros);
        $this->assertSessionHasErrors();
    }
    public function testLoginError2()
    {
        // Contraseña incorrecta
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'user' => 'admin',
            'password' => 'secreto',
        ];
        $response = $this->call('POST', 'login', $parametros);
        $this->assertSessionHasErrors();
    }
    public function testLoginError3()
    {
        // Usuario y contraseña vacíos
        Session::start();
        $parametros = [
            '_token' => csrf_token(), // Obteniendo el csrf token
            'user' => '',
            'password' => '',
        ];
        $response = $this->call('POST', 'login', $parametros);
        $this->assertSessionHasErrors();
    }

}
