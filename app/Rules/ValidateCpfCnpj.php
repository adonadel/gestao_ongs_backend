<?php

namespace App\Rules;

use App\Utils\CNPJUtils;
use App\Utils\CPFUtils;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCpfCnpj implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validate = false;

        if (strlen($value) === 14) {
            $validate = CPFUtils::validateCpf($value);
            $type = 'CPF';
        }elseif (strlen($value) === 18) {
            $validate = CNPJUtils::validateCnpj($value);
            $type = 'CNPJ';
        }else {
            $fail('Tamanho incorreto para o campo :attribute');
        }

        if (!$validate) {
            $fail("{$type} inválido");
        }
    }
}
