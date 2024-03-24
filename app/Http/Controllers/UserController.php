<?php

namespace App\Http\Controllers;

use App\Extensions\CustomPassword;
use App\Http\Services\User\CreateUserService;
use App\Http\Services\User\DeleteUserService;
use App\Http\Services\User\DisableUserService;
use App\Http\Services\User\EnableUserService;
use App\Http\Services\User\QueryUserService;
use App\Http\Services\User\UpdateUserService;
use App\Models\Role;
use App\Repositories\UserRepository;
use App\Rules\UniqueCpfCnpj;
use App\Rules\UniqueEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password as PasswordForReset;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    function login(Request $request)
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
                'expires_in' => auth()->factory()->getTTL() * 60 * 8
            ]
        ]);
    }

    function logout(Request $request)
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

    function getLoggedUser()
    {
        if (! auth()->user()) {
            throw new \Exception('Nenhum usuário logado');
        }else {
            return auth()->user();
        }
    }

    function create(Request $request)
    {
        try {
            DB::beginTransaction();

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

            $user = $service->create($validated);

            DB::commit();

            return $user;
        }catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    function update(Request $request, int $id)
    {
        try {
            DB::beginTransaction();

            $service = new UpdateUserService();

            $validated = $request->validate([
                'password' => [
                    'required', Password::min(6)->mixedCase()->letters()->numbers()
                ],
                'role_id' => ['required', 'int', Rule::exists(Role::class, 'id')],
                'person' => 'array|required',
                'person.id' => 'required|int',
                'person.name' => 'required|string',
                'person.email' => ['required', 'email', new UniqueEmail(new UserRepository())],
                'person.cpf_cnpj' => ['required', 'string', new UniqueCpfCnpj(new UserRepository())],
            ]);

            $updated = $service->update($validated, $id);

            DB::commit();

            return $updated;
        }catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    function delete(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new DeleteUserService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário excluído com sucesso!'
            ]);
        }catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    function enable(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new EnableUserService();

            $service->enable($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário ativado com sucesso!'
            ]);
        }catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    function disable(int $id)
    {
        try {
            DB::beginTransaction();

            $service = new DisableUserService();

            $service->disable($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário desativado com sucesso!'
            ]);
        }catch(\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    function forgotPassword(Request $request)
    {
        $request->validate([
           'email' => 'required|email'
        ]);

        $userRepository = new UserRepository();

        $user = $userRepository->getByEmail($request->email);

        if (!$user) {
            return response()->json([
                'message' => 'Erro ao enviar email de recuperação!'
            ]);
        }
        $customPassword = new CustomPassword();

        $status = $customPassword->sendResetLink([
            'email' => $user->getEmailForPasswordReset(),
        ]);

        if ($status === PasswordForReset::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link para recuperar senha enviado!'
            ]);
        }else {
            return response()->json([
                'message' => 'Erro ao enviar email de recuperação!'
            ]);
        }
    }

    function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required', Password::min(6)->mixedCase()->letters()->numbers()
            ],
        ]);
        $customPassword = new CustomPassword();

        $status = $customPassword->reset($request->only('email', 'password', 'token'));


        if ($status === PasswordForReset::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Senha resetada com sucesso!'
            ]);
        }else {
            return response()->json([
                'message' => 'Erro ao resetar senha!'
            ]);
        }
    }
}
