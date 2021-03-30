<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\User as UserResource;


class UserController extends ApiController
{
    // mostrar un usuario
    public function index()
    {
        return new UserCollection(User::paginate());
    }

    //Verifica si el usuario esta autentificado
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
            try {
                if (! $token = JWTAuth::attempt($credentials)) {
                    return $this->errorResponse('invalid_credentials',400) ;
                }
            } catch (JWTException $e) {
                return $this->errorResponse('could_not_create_token',500);
                
            }
        return response()->json(compact('token'));
    }
    
    public function create()
    {
        //
    }

    // registra o crea un usuario y crea el token (store)
    public function store(Request $request)
    {
        $validated =  $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // si hay un error laravel envia automarticamente

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        $token = JWTAuth::fromUser($user);
        $userR = new UserResource($user);
        return $this->showOne($userR,201);
    }

    // Muestra el Usuario
    public function show(User $user)
    {
        $usu = new UserResource($user);
        return $this->showOne($usu,201);
    }

    //Verifica si el usuario ingrasado existe y retorna un ususario - show
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return $this->errorResponse('user_not_found', 404);
            }
        } catch (TokenExpiredException $e) {
            return $this->errorResponse('token_expired', $e);
        } catch (TokenInvalidException $e) {
            return $this->errorResponse('token_invalid', $e);
        } catch (JWTException $e) {
            return $this->errorResponse('token_absent', $e);
        }
            return response()->json(compact('user'));
    }
    
    //Actualiza el ususario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'. $user->id,
            'password' => 'required|string|min:6|confirmed',
        ]);

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar',422);
        }

        $user->update($request->all());
        $userR = new UserResource($user);
        return $this->showOne($userR);

    }

    //Elimina el usuario
    public function destroy(User $user)
    {
        $user->delete();
        $userR = new UserResource($user);
        return $this->showOne($userR,204);
    }
}
