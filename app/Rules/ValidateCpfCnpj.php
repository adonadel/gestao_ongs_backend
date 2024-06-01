<?php

namespace App\Rules;

use App\Utils\CNPJUtils;
use App\Utils\CPFUtils;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCpfCnpj implements ValidationRule
{
    protected $cpfCnpj;

    public function __construct(String $cpfCnpj)
    {
        $this->cpfCnpj = $cpfCnpj;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $validate = false;
        
        if (strlen($this->cpfCnpj) === 11) {
            $validate = CPFUtils::validateCpf($this->cpfCnpj);
        }elseif (strlen($this->cpfCnpj) === 14) {
            $validate = CNPJUtils::validateCnpj($this->cpfCnpj);
        }else {
            $fail('Tamanho incorreto para o campo :attribute');
        }

        if (!$validate) {
            $fail(':attribute inválido');
        }
    }
}
