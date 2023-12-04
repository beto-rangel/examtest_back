<?php

namespace App\Http\Controllers\v1;
use App\helpers\JsonResponse;
use App\Repositories\Eloquent\UserRepository as User;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Traits\JWTTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Eloquent\InternalEventRepository as Internal;
use Carbon\Carbon;

class UserController extends Controller
{
    use JWTTrait;

    protected $user;
    protected $hidden = ['password', 'remember_token'];
    protected $internal;


    /**
     * UserController constructor.$userId = Auth::id();
     *
     * @param User $user
     *
     */

    public function __construct(User $user, Internal $internal)
    {
        $this->user = $user;
        $this->internal = $internal;
    }

    /**
     * Devuelve todas los usuarios filtrados por status Activo y no eliminados de forma logica en el almacenamiento.
     *
     * @return \Illuminate\Http\response
     */

    public function index()
    {

        $users = DB::table('users')
                ->where('status', 'Activo')
                ->where('deleted_at', null)
                ->select('id', 'name', 'email', 'last_name', 'phone', 'status', 'role', 'created_at', 'updated_at', 'last_login', 'photo')
                ->get();
        
        return JsonResponse::collectionResponse($users);
    }

    /**
     * nuevo usuario  en el sistema
     *
     * @param \Illuminate\http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $now = Carbon::now('America/Mexico_City');
        $user = Auth::user();

        $name      = array_get($request, 'name');
        $last_name = array_get($request, 'last_name');
        $phone     = array_get($request, 'phone');
        $email     = array_get($request, 'email');
        $status    = array_get($request, 'status');
        $password  = array_get($request, 'password');
        $photo     = array_get($request, 'photo');
        $role      = array_get($request, 'role');

        $usuario_id =  DB::table('users')->insertGetId(array(
            'name'       => $name,
            'last_name'  => $last_name,
            'phone'      => $phone,
            'email'      => $email,
            'status'     => $status,
            'password'   => Hash::make($password),
            'photo'      => $photo,
            'role'       => $role,            
            'created_at' => $now,
            'updated_at' => $now

        ));

        $user_test = DB::table('users')->where('id', $usuario_id)->get();

        //registramos en la tabla de logs la creacion de un nuevo suuario
        $this->internal->create(array(
            'user_id'   => $user['id'],
            'evento'    => 'El usuario: '. $user['name'] . ' ha creado el usuario con ID: ' . $usuario_id,
            'created_at'    => $now,
            'updated_at'    => $now
        ));

        return JsonResponse::singleResponse([
            "message" => "se ha registrado un nuevo usuario",
            "User" => $user_test
        ],200);
    }

    /**
     * actualizamos el usuario mediante su ID  en el sistema
     *
     * @param \Illuminate\http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $user_id)
    {
        $data = $request->all();
        $now = Carbon::now('America/Mexico_City');
        $user = Auth::user();

        $name      = array_get($request, 'name');
        $last_name = array_get($request, 'last_name');
        $phone     = array_get($request, 'phone');
        $email     = array_get($request, 'email');
        $status    = array_get($request, 'status');
        $photo     = array_get($request, 'photo');
        $role      = array_get($request, 'role');

        if($photo == null || $photo == ''){
            DB::table('users')->where('id', $user_id)->update(array(
                'name'       => $name,
                'last_name'  => $last_name,
                'phone'      => $phone,
                'email'      => $email,
                'status'     => $status,
                'role'       => $role,            
                'updated_at' => $now
            ));   
        }else{
            DB::table('users')->where('id', $user_id)->update(array(
                'name'       => $name,
                'last_name'  => $last_name,
                'phone'      => $phone,
                'email'      => $email,
                'status'     => $status,
                'photo'      => $photo,
                'role'       => $role,            
                'updated_at' => $now
            ));
        }

        //registramos en la tabla de logs la actualizacion del usuario mediante su ID
        $this->internal->create(array(
            'user_id'   => $user['id'],
            'evento'    => 'El usuario: '. $user['name']. ' ha editado el usuario con ID: ' . $user_id,
            'created_at'    => $now,
            'updated_at'    => $now
        ));

        return JsonResponse::singleResponse([
            "message" => "se ha actualizado un nuevo usuario",
        ],200);
    }

    /**
     * obtenemos los datos de un usuario mediante su ID en el sistema
     *
     * @param \Illuminate\http\Request $request
     *
     * @return \Illuminate\Http\Response
     */

    public function show($user_id)
    {
        try { 
            $usuarios = $this->user->findOrFail($user_id); 

            return JsonResponse::singleResponse($usuarios->toArray()); 
 
        } catch (ModelNotFoundException $exception) { 
            \Log::error("Mostrando un usuario...", [ 
                "model"   => $exception->getModel(), 
                "message" => $exception->getMessage(), 
                "code"    => $exception->getCode() 
            ]);  
 
            return JsonResponse::errorResponse("No se puede mostrar el usuario, informacion no encontrada", 404); 
        } 
    }

    /**
     *Elimina un usuario mediante eliminacion logica en espesifico dentro del almacenamiento.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $now = Carbon::now('America/Mexico_City');
            $user_delete = $this->user->find($id);
            $user = Auth::user();
            $user_id = Auth::user()->id;
            
            //validamos si el usuario quiere eliminarse él mismo, si es asi, no lo dejamos
            if($user_id == $id){
                return JsonResponse::errorResponse("No es posible auto destruirte", 404);
            }else{
                //registramos en la tabla de losg la eliminacion logica del usuario
                $this->internal->create(array(
                    'user_id'   => $user['id'],
                    'evento'    => 'El usuario: '.$user['name']. ' ha eliminado el usuario con ID: ' . $id,
                    'created_at'    => $now,
                    'updated_at'    => $now
                ));
                $this->user->delete($id); 
            }
            
            return JsonResponse::singleResponse([ "message" => "El usuario ha sido eliminado." ]);
        } catch (ModelNotFoundException $exception) {
            \Log::error("Eliminando usuario...", [
                "model"   => $exception->getModel(),
                "message" => $exception->getMessage(),
                "code"    => $exception->getCode()
            ]);

            return JsonResponse::errorResponse("No es posible eliminar el usuario, informacion no encontrado.", 404);
        }

    }

    // Actualización de Contraseña Usuario
    public function updatePassword(Request $request,$user_id )
    {
        $data = $request->all();
        $now = Carbon::now('America/Mexico_City');
        $user_pass = $this->user->find($user_id);

        $pass_anterior = array_get($request, 'password_anterior');
        $pass_anterior2 = Hash::check($pass_anterior, $user_pass['password']);

        if($pass_anterior2 == true){
            DB::table('users')->where('id', $user_id)
                ->update(array(
                'password'      => Hash::make(array_get($request, 'password')),
                'updated_at'    => $now
            ));
        }else{
            return JsonResponse::errorResponse("No es posible cambiarla, password anterior es incorrecto.", 404);
        }

        $user = Auth::user();
                $this->internal->create(array(
                'user_id'   => $user['id'],
                'evento'    => 'El usuario: '.$user['name']. ' ha actualizado la contraseña del usuario: ' .$user_pass['name'],
                'created_at'    => $now,
                'updated_at'    => $now
            ));

        return JsonResponse::singleResponse(["message" => "Se ha actualizado la contraseña del Usuario"]);

    }

    /**
     * generacion del documento PDF (lista de usuarios)
     *
     */

    public function getUsersPdf(){

        $users = DB::table('users')
                ->where('status', 'Activo')
                ->where('deleted_at', null)
                ->select('id', 'name', 'email', 'last_name', 'phone', 'status', 'role', 'created_at', 'updated_at', 'last_login', 'photo')
                ->get(); 

        $view =  \View::make('users.users', compact('users'))->render();

        $now = Carbon::now('America/Mexico_City');
        $date = $now->format('Y-m-d');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);
        //$pdf->setPaper('A4', 'landscape');
        return $pdf->download($date. ' '. 'Usuarios.pdf');
        
    }
}
