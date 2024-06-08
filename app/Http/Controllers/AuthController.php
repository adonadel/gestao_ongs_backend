<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password', 'remember']);

        $remember = (bool) data_get($credentials, 'remember');

        $repository = new UserRepository();

        $user = $repository->getByEmail(data_get($credentials, 'email'));

        if (!$user || !Hash::check(data_get($credentials, 'password'), $user->password)) {
            abort(401, 'Não autorizado');
        }

        $token = auth()->login($user, $remember);

        return response()->json([
            'data' => [
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL()
            ]
        ]);
    }

    public function logout(Request $request)
    {
        if (! auth()->user()) {
            throw new \Exception('Nenhum usuário logado');
        }

        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Desconectado do sistema'
        ]);
    }

    public function me()
    {
        if (! auth()->user()) {
            throw new \Exception('Nenhum usuário logado');
        }

        return auth()->user()->load(['person.address', 'role', 'role.permissions', 'person.profilePicture']);
    }

    public function refreshToken(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json([
                    'error' => 'Token invalido'
                ], 401);
            }

            $refreshedToken = Auth::guard('api')->refresh($token);
            return response()->json([
                'newToken' => $refreshedToken
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'error' => $exception->getMessage()
            ], 400);
        }
    }

}
