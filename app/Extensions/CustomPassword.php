<?php

namespace App\Extensions;

use App\Mail\PasswordReset;
use App\Repositories\PasswordResetTokenRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class CustomPassword extends Password
{
    private PasswordResetTokenRepository $repository;

    public function __construct()
    {

        $this->repository = new PasswordResetTokenRepository();
    }

    public function sendResetLink(array $credentials, ?\Closure $callback = null): string
    {
        $repository = new UserRepository();

        $email = data_get($credentials, 'email');

        $user = $repository->getByEmail($email);

        if (is_null($user)) {
            return static::INVALID_USER;
        }

        $token = $this->createTokenWithEmail($email);

        if ($callback) {
            return $callback($user, $token) ?? static::RESET_LINK_SENT;
        }

        Mail::to($email)->send(new PasswordReset($user, $token));

        return static::RESET_LINK_SENT;
    }

    public function reset(array $credentials, ?\Closure $callback = null): string
    {
        if (!$this->checkIfTokenExists($credentials)) {
            throw new \Exception('Token informado é inválido');
        }

        $userRepository = new UserRepository();

        $user = $userRepository->getByEmail(data_get($credentials, 'email'));

        $userRepository->update($user, [
            'password' => Hash::make(data_get($credentials, 'password')),
        ]);

        $this->deleteToken($credentials);

        return static::PASSWORD_RESET;
    }

    private function createTokenWithEmail(mixed $email)
    {
        $token = Str::uuid()->toString();

        $this->repository->create([
            'token' => $token,
            'email' => $email
        ]);

        return $token;
    }

    private function checkIfTokenExists(array $credentials)
    {

        return $this->repository->newQuery()
            ->where('email', data_get($credentials, 'email'))
            ->where('token', data_get($credentials, 'token'))
            ->exists();
    }

    private function deleteToken(array $credentials)
    {

        return $this->repository->newQuery()
            ->where('email', data_get($credentials, 'email'))
            ->where('token', data_get($credentials, 'token'))
            ->delete();
    }
}
