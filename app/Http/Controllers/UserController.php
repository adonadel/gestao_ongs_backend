<?php

namespace App\Http\Controllers;

use App\Enums\UserTypeEnum;
use App\Extensions\CustomPassword;
use App\Http\Requests\UserRequest;
use App\Http\Services\User\CreateUserService;
use App\Http\Services\User\DeleteUserService;
use App\Http\Services\User\DisableUserService;
use App\Http\Services\User\EnableUserService;
use App\Http\Services\User\QueryUserService;
use App\Http\Services\User\UpdateUserService;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password as PasswordForReset;
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

            $user = $service->create($request->all(), null);

            DB::commit();

            return $user;
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function createExternal(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            $service = new CreateUserService();

            $user = $service->create($request->all(), UserTypeEnum::EXTERNAL);

            DB::commit();

            return $user;
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        Gate::authorize('update', auth()->user());

        try {
            DB::beginTransaction();

            $service = new UpdateUserService();

            $updated = $service->update($request->all(), $id);

            DB::commit();

            return $updated;
        }catch(\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function updateExternal(Request $request, int $id)
    {
        $validated = $request->validate([
            'person' => 'array|required',
            'person.name' => 'required|string',
            'person.phone' => 'nullable|string',
            'person.address_id' => 'nullable|int',
            'person.address' => 'nullable|array',
            'person.address.id' => 'nullable|int',
            'person.address.zip' => 'nullable|string',
            'person.address.street' => 'nullable|string',
            'person.address.number' => 'nullable|string',
            'person.address.neighborhood' => 'nullable|string',
            'person.address.city' => 'nullable|string',
            'person.address.state' => 'nullable|string',
            'person.address.complement' => 'nullable|string',
            'person.address.longitude' => 'nullable|string',
            'person.address.latitude' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $service = new UpdateUserService();

            $updated = $service->updateExternal($validated, $id);

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

        try {
            DB::beginTransaction();

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
                DB::commit();

                return response()->json([
                    'message' => 'Link para recuperar senha enviado!'
                ]);
            }else {
                DB::rollBack();
                return response()->json([
                    'message' => 'Erro ao enviar email de recuperação!'
                ]);
            }
        }catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
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

        try {
            DB::beginTransaction();

            $status = $customPassword->reset($request->only('email', 'password', 'token'));

            if ($status === PasswordForReset::PASSWORD_RESET) {
                DB::commit();

                return response()->json([
                    'message' => 'Senha resetada com sucesso!'
                ]);
            }else {
                DB::rollBack();

                return response()->json([
                    'message' => 'Erro ao resetar senha!'
                ]);
            }
        }catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }

    public function getUserByIdExternal(int $id)
    {
        $service = new QueryUserService();

        return $service->getUserByIdExternal($id);
    }
}
