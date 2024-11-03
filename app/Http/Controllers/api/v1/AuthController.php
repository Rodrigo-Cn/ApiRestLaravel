<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Repositories\Eloquent\AuthRepository;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authRepository->register($request->validated());

        Log::info('Usuário registrado com sucesso.', ['user_id' => $user->id]);

        return response()->json(['success' => 'Usuário registrado com sucesso!'], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authRepository->login($request->validated());

        if (!$user) {
            Log::warning('Tentativa de login falhou.', ['email' => $request->email]);
            return response()->json(['error' => 'Credenciais inválidas!'], 401);
        }

        $token = $user->createToken('MyApp')->plainTextToken;

        Log::info('Login bem-sucedido.', ['user_id' => $user->id]);

        return response()->json(['token' => $token]);
    }
    
    public function logout(Request $request)
    {
        $this->authRepository->logout($request->user());

        Log::info('Logout realizado com sucesso.', ['user_id' => $request->user()->id]);

        return response()->json(['success' => 'Logout realizado com sucesso!']);
    }
}
