<?php

namespace App\Http\Controllers;

use App\Extensions\CustomPassword;
use App\Http\Requests\UserRequest;
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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password as PasswordForReset;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {
        Gate::authorize('view', auth()->user());

        $service = new QueryUserService();

        return $service->getUsers($request->all());
    }

    public function getUserById(int $id)
    {
        Gate::authorize('view', auth()->user());

        $service = new QueryUserService();

        return $service->getUserById($id);
    }

    public function create(UserRequest $request)
    {
        Gate::authorize('create', auth()->user());

        try {
            DB::beginTransaction();

            $service = new CreateUserService();

            $user = $service->create($request->all(), false);

            DB::commit();

            return $user;
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception('Ocorreu um erro ao criar usuário: ' . $exception->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        Gate::authorize('update', auth()->user());

        try {
            DB::beginTransaction();

            $service = new UpdateUserService();

            $validated = $request->validate([
                'password' => [
                    'nullable', Password::min(6)->mixedCase()->letters()->numbers()
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
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function delete(int $id)
    {
        Gate::authorize('delete', auth()->user());

        try {
            DB::beginTransaction();

            $service = new DeleteUserService();

            $service->delete($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário excluído com sucesso!'
            ]);
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function enable(int $id)
    {
        Gate::authorize('update', auth()->user());

        try {
            DB::beginTransaction();

            $service = new EnableUserService();

            $service->enable($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário ativado com sucesso!'
            ]);
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function disable(int $id)
    {
        Gate::authorize('update', auth()->user());

        try {
            DB::beginTransaction();

            $service = new DisableUserService();

            $service->disable($id);

            DB::commit();

            return response()->json([
                'message' => 'Usuário desativado com sucesso!'
            ]);
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function forgotPassword(Request $request)
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

    public function resetPassword(Request $request)
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

    public function createExternal(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateUserService();

            $user = $service->create($request->all(), true);

            DB::commit();

            return $user;
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }
}
