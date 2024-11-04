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

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Registra um novo usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João Silva", description="Nome do usuário"),
     *             @OA\Property(property="email", type="string", example="joao@example.com", description="Email do usuário"),
     *             @OA\Property(property="password", type="string", example="senha123", description="Senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Usuário registrado com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro de validação nos dados do usuário",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Dados inválidos")
     *         )
     *     )
     * )
     */
    public function register(RegisterRequest $request)
    {
        $user = $this->authRepository->register($request->validated());

        Log::info('Usuário registrado com sucesso.', ['user_id' => $user->id]);

        return response()->json(['success' => 'Usuário registrado com sucesso!'], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Realiza o login do usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="joao@example.com", description="Email do usuário"),
     *             @OA\Property(property="password", type="string", example="senha123", description="Senha do usuário")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...", description="Token de autenticação")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Credenciais inválidas!")
     *         )
     *     )
     * )
     */
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
    
    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Realiza o logout do usuário",
     *     tags={"Auth"},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Logout realizado com sucesso!")
     *         )
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $this->authRepository->logout($request->user());

        Log::info('Logout realizado com sucesso.', ['user_id' => $request->user()->id]);

        return response()->json(['success' => 'Logout realizado com sucesso!']);
    }
}
