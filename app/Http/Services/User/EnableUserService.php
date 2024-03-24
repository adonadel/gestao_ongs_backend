<?php

namespace App\Http\Services\User;


use App\Enums\UserStatusEnum;
use App\Exceptions\UserAlreadyEnabledOrDisabledException;
use App\Repositories\UserRepository;

class EnableUserService
{
    function enable(int $id)
    {
        $userRepository = new UserRepository();

        $user = $userRepository->getById($id);

        if ($user->status === UserStatusEnum::DISABLED) {
            throw new UserAlreadyEnabledOrDisabledException('Usuário já ativado');
        }

        $userRepository->update($user, [
            'status' => UserStatusEnum::ENABLED
        ]);

        return true;
    }
}
