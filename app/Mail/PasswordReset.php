<?php

namespace App\Mail;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Mail\Mailable;

class PasswordReset extends Mailable
{
    private Model $user;
    private string $token;

    public function __construct(Model $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function build()
    {
        return $this->view('emails.passwordReset', [
            'user' => $this->user,
            'recoveryLink' => env('APP_FRONTEND_URL') . $this->token,
        ]);
    }
}
