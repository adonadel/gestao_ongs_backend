<?php

namespace App\Http\Controllers;

use App\Http\Services\User\CreateUserService;
use App\Http\Services\User\QueryUserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            abort(401);
        }

        return response()->json([
            'data' => [
                'token' => $token,
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ]);
    }

    function logout()
    {
        if (! auth()->user()) {
            throw new \Exception('Nenhum usuário logado');
        }

        auth()->logout();

        return response()->json([
            'message' => 'Desconectado do sistema'
        ]);
    }

    function getUsers(Request $request)
    {
        $service = new QueryUserService();

        return $service->getUsers($request->all());
    }

    function getUserById(int $id)
    {
        $service = new QueryUserService();

        return $service->getUserById($id);
    }

    function create(Request $request)
    {
        $service = new CreateUserService();

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6|max:255',
        ]);

        return $service->create($validated);
    }

    function update(Request $request, int $id)
    {
        $service = new UpdateUserService();

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => 'required|string|min:6|max:255',
        ]);

        return $service->update($validated, $id);
    }

}
