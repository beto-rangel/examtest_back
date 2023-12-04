<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\helpers\JsonResponse;
use Carbon\Carbon;
use App\Repositories\Eloquent\InternalEventRepository as Internal;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\JWTTrait;
use DB;

class APILoginController extends Controller
{
     public function __construct(Internal $internal)
    {
        $this->internal = $internal;
    }

    use JWTTrait;

    public function login()
    {
        // Obtén el número de teléfono desde la solicitud
        $telefono = request('phone');

        // Busca al usuario por el número de teléfono
        $user = User::where('phone', $telefono)->first();

        // Intenta autenticar y obtener el token utilizando la autenticación de API
        if (!$token = auth('api')->login($user)) {
            // Si las credenciales son incorrectas, enviamos un error no autorizado en formato JSON
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $now = Carbon::now('America/Mexico_City');
        
        if ($user) {
            $this->internal->create([
                'user_id'       => $user->id,
                'evento'        => 'El usuario: ' . $user->name . ' ha iniciado sesión',
                'created_at'    => $now,
                'updated_at'    => $now
            ]);
        }

        DB::table('users')->where('id', $user->id)->update(array(
            'last_login' => $now,
        ));

        return response()->json([
            'token'   => $token,
            'type'    => 'bearer', // puedes omitir esto
            'expires' => auth('api')->factory()->getTTL() * 4800, // tiempo de expiración
            'Usuario' => $user->toArray(),
        ]);
    }
}
