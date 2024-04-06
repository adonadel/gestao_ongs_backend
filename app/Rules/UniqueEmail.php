<?php

namespace App\Rules;

use App\Repositories\Repository;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class UniqueEmail implements ValidationRule
{
    protected $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = $this->repository->newQuery()
            ->where('status', 'enabled')
            ->whereHas('person', function ($query) use ($value) {
                return $query->where('email', Str::lower($value));
            })->exists();

        if ($exists) {
            $fail(':attribute já existente no banco de dados');
        }
    }
}
