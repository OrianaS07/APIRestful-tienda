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
use App\Mail\UserCreatedMailable;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{
    // mostrar un usuario
    public function index()
    {
        return new UserCollection(User::paginate());
    }

    //Verifica si el usuario esta autentificado - verify
    public function login(Request $request)
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
    
    public function verify($token)
    {
        $user = User::where('verification_token',$token)->firstOrFail();
        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;

        $user->save();

        return $this->showMenssage('The account was successfully verified');
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
            'verified' => User::USUARIO_NO_VERIFICADO,
            'verification_token' => User::generarVerificationToken()
        ]);

        // $token = JWTAuth::fromUser($user);
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
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,'. $user->id,
            'password' => 'string|min:6|confirmed',
        ]);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        
        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if(!$user->isDirty()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar',422);
        }

        $user->save();
        return $this->showOne(new UserResource($user));

    }

    //Elimina el usuario
    public function destroy(User $user)
    {
        $user->delete();
        $userR = new UserResource($user);
        return $this->showOne($userR,204);
    }

    public function resend(User $user)
    {
        if($user->esVerificado()){
            return $this->errorResponse('Este usuario ya ha sido verificado',400);
        }

        retry(5, function () use ($user){
            Mail::to($user)->send(new UserCreatedMailable($user));
        }, 100);
        

        return $this->showMenssage('El correo de Verficacion se ha reenviado');
    }   

}
