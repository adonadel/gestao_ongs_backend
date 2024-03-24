<?php

namespace App\Extensions;

use App\Mail\PasswordReset;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CustomPassword extends Password
{

    public function sendResetLink(array $credentials, ?\Closure $callback = null): string
    {
        $repository = new UserRepository();

        $email = data_get($credentials, 'email');

        $user = $repository->getByEmail($email);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        $token = Str::uuid()->toString();

        if ($callback) {
            return $callback($user, $token) ?? static::RESET_LINK_SENT;
        }

        Mail::to($email)->send(new PasswordReset($user, $token));

        return static::RESET_LINK_SENT;
    }
}
