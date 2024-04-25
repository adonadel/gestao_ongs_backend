<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class RoleRepository extends Repository
{
    protected function getModelClass()
    {
        return Role::class;
    }

    public function getRoles(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'name');

        $query = $this->newQuery();

        $query
            ->with('permissions')
            ->when($search, function(Builder $query, $search){
                $query
                    ->where('name', 'ilike', "%{$search}%");
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
