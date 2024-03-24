<?php

namespace App\Http\Services\User;

use App\Repositories\UserRepository;
use App\Utils\CPFUtils;
use App\Utils\StringUtils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class QueryUserService
{
    function getUsers(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'name');

        $query = (new UserRepository())->newQuery();

        $query
            ->with(['person.address', 'role', 'role.permissions'])
            ->when($search, function(Builder $query, $search){
                $query->whereHas('person', function (Builder $query) use ($search){
                   $check = StringUtils::checkIfStringStartWithNumber($search);
                   return $query

                       ->where('name', 'ilike', "%{$search}%")
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

    public function getUserById(int $id)
    {
        return (new UserRepository())->getById($id);
    }
}
