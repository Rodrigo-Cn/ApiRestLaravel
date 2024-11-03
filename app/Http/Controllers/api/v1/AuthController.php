<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
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
        $this->authRepository->register($request->validated());

        return response()->json(['success' => 'Usuário registrado com sucesso!'], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->authRepository->login($request->validated());

        if (!$user) {
            return response()->json(['error' => 'Credenciais inválidas!'], 401);
        }

        $token = $user->createToken('MyApp')->plainTextToken;

        return response()->json(['token' => $token]);
    }
    
    public function logout(Request $request)
    {
        $this->authRepository->logout($request->user());

        return response()->json(['success' => 'Logout realizado com sucesso!']);
    }
}
