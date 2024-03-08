<?php

namespace App\Http\Services\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class QueryUserService
{
    function getUsers(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $name = data_get($filters, 'name');

        $query = User::query();

        $query
            ->when($name, function(Builder $query, $name){
                $query->where('name', 'ilike', "%{$name}%");
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }

    public function getUserById(int $id)
    {
        return User::query()->find($id);
    }
}
