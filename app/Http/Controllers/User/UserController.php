<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    // mostrar un usuario
    public function index()
    {
        $users = User::all();
        return response()->json(['data' => $users],200);
        
    }

    //Verifica si el usuario esta autentificado
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
            try {
            if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'invalid_credentials'], 400);
            }
            } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
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
        return response()->json(compact('user','token'),201);
    }

    // Muestra el Usuario
    public function show(User $user)
    {
        return response()->json(['data' => $user],200);
    }

    //Verifica si el usuario ingrasado existe y retorna un ususario - show
    public function getAuthenticatedUser()
    {
        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e);
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e);
        } catch (JWTException $e) {
            return response()->json(['token_absent'], $e);
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
            return response()->json(['error' => 'Se debe especificar al menos un valor diferentee para actualizar',
                                    'code' => 422], 422);
        }

        $user->update($request->all());
        return response()->json(['data' => $user],200);

    }

    //Elimina el usuario
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(null,204);
    }
}
