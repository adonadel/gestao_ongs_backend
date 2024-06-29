<?php

namespace App\Repositories;

use App\Models\User;
use App\Utils\CPFUtils;
use App\Utils\StringUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class UserRepository extends Repository
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function getUsers(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'search');

        $query = $this->newQuery();

        $query
            ->with(['person.address', 'role', 'role.permissions', 'person.profilePicture'])
            ->when($search, function(Builder $query, $search){
                $query->whereHas('person', function (Builder $query) use ($search){
                   $check = StringUtils::checkIfStringStartWithNumber($search);
                   return $query
                       ->whereRaw('unaccent(name) ilike unaccent(?)', ["%{$search}%"])
                       ->orWhere('email', 'ilike', "%{$search}%")
                       ->when($check, function ($query) use ($search){
                           $cleanedString = CPFUtils::removeNonAlphaNumericFromString($search);
                           $query->orWhere(
                                DB::raw("regexp_replace(\"cpf_cnpj\" , '[^0-9]*', '', 'g')"),
                                'ilike',
                                "{$cleanedString}%"
                            );
                    });
                });
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }

    public function getByEmail(string $email)
    {
        return $this->newQuery()
            ->with('role.permissions')
            ->whereHas('person', function (Builder $query) use ($email) {
                return $query->where('email', $email);
            })->first();
    }
}
