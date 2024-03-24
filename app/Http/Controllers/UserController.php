<?php

namespace App\Http\Controllers;

use App\Http\Services\User\CreateUserService;
use App\Http\Services\User\QueryUserService;
use App\Http\Services\User\UpdateUserService;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Rules\UniqueCpfCnpj;
use App\Rules\UniqueEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        $repository = new UserRepository();

        $user = $repository->newQuery()
            ->whereHas('person', function (Builder $query) use ($credentials) {
                $query->where('email', data_get($credentials, 'email'));
            })
            ->first();

        if (!$user || !Hash::check(data_get($credentials, 'password'), $user->password)) {
            abort(401, 'Não autorizado');
        }

        $token = auth()->login($user);

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
            'password' => [
                'required', Password::min(6)->mixedCase()->letters()->numbers()
            ],
            'role_id' => ['required', 'int', Rule::exists(Role::class, 'id')],
            'person' => 'array|required',
            'person.name' => 'required|string',
            'person.email' => ['required', 'email', new UniqueEmail(new UserRepository())],
            'person.cpf_cnpj' => ['required', 'string', new UniqueCpfCnpj(new UserRepository())],
        ]);

        return $service->create($validated);
    }

    function update(Request $request, int $id)
    {
        $service = new UpdateUserService();

        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string',
            'password' => [
                'required', Password::min(6)->mixedCase()->letters()->numbers()
            ],
        ]);

        return $service->update($validated, $id);
    }

}
