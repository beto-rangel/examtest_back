<?php

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
    return view('welcome');
});

//descarga de archivos
Route::group(['prefix' => 'descarga'], function (\Illuminate\Routing\Router $router) {

    $router->get('storage/{seccion}/{id}/{carpeta}/{file}', function ($seccion,$id,$carpeta,$archivo) { //obtorsener archivos
        $public_path = storage_path();
        $ruta =$seccion.'/'.$id.'/'.$carpeta.'/' .$archivo;
        $url = $public_path . '/app/'.$ruta;


        //verificamos si el archivo existe y lo retornamos

        if (Storage::exists($ruta)) {
            // dd($url);
            return Response::make(file_get_contents($url), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="'.$archivo.'"'
            ]);

        }
        //si no se encuentra lanzamos un error 404.
        // abort(404);
    });


    $router->get('storage/{file}', function ($archivo) { //obtorsener archivos
        $public_path = public_path();
        $url = $public_path . '/storage/' . $archivo;
        //verificamos si el archivo existe y lo retornamos

        if (Storage::exists($archivo)) {
            return response()->download($url);

        }
        //si no se encuentra lanzamos un error 404.
        // abort(404);
    });
});

//ruta para hacer login al sistema
Route::post('login', 'APILoginController@login');

//proteccion de rutas mediante JWT Token
Route::group(['middleware' => ['jwt.auth', 'cors'],'prefix' => 'v1'], function () {

    Route::group(['namespace' => 'v1'], function () {
        
        //Rutas, endpoints

        //lista de usuarios
        Route::get('usuarios', 'UserController@index');

        //Obtener un usuario mediante su ID
        Route::get('usuarios/{user_id}', 'UserController@show');

        //generacion de pdf mostrando lista de usuarios
        Route::get('getUsersByPdf', 'UserController@getUsersPdf');

        //creacion de un usuario
        Route::post('usuarios', 'UserController@store');

        //actualizacion de un usuario mediante su ID
        Route::post('usuarios/{user_id}', 'UserController@update');

        //eliminacion logica de un usuario
        Route::delete('usuarios/{user_id}', 'UserController@destroy');

        //cambio de password de un usuario mediante su ID
        Route::put('usuarios/{user_id}/resetPassword', 'UserController@updatePassword');

        //guardar archivo
        Route::group(['prefix' => 'archivos'], function (\Illuminate\Routing\Router $router) {
            $router->post('storage/create', 'StorageController@save');
        });
        
    });

});



