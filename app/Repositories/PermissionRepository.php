<?php

namespace App\Repositories;

use App\Models\Permission;

class PermissionRepository extends Repository
{
    protected function getModelClass()
    {
        return Permission::class;
    }

    public function getPermissions(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'search');

        $query = $this->newQuery();

        $query
            ->when($search, function(Builder $query, $search){
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('display_name', 'ilike', "%{$search}%");
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
