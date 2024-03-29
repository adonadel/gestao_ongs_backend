<?php

namespace App\Http\Services\User;


use App\Enums\UserStatusEnum;
use App\Exceptions\UserAlreadyEnabledOrDisabledException;
use App\Repositories\UserRepository;

class DisableUserService
{
    public function disable(int $id)
    {
        $userRepository = new UserRepository();

        $user = $userRepository->getById($id);

        if ($user->status === UserStatusEnum::DISABLED) {
            throw new UserAlreadyEnabledOrDisabledException('Usuário já desativado');
        }

        $userRepository->update($user, [
            'status' => UserStatusEnum::DISABLED
        ]);

        return true;
    }
}
