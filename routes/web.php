<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	$dsn = 'mysql:dbname=apiresful;host=127.0.0.1';
	$usuario = 'root';
	$contraseña = '';

	try {
		$gbd = new PDO($dsn, $usuario, $contraseña);
		echo 'Conexión establecida';
	} catch (PDOException $e) {
		echo 'Falló la conexión: ' . $e->getMessage();
	}
	// echo phpinfo();
    // return view('welcome');
});
