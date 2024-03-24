<?php

namespace App\Traits;

trait HasPersonEmail
{
    public function getEmailForPasswordReset()
    {
        return $this->person->email;
    }
}
