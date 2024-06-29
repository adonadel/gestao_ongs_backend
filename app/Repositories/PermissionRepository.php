<?php

namespace App\Repositories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Builder;

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
                    ->whereRaw('unaccent(name) ilike unaccent(?)', ["%{$search}%"])
                    ->orWhereRaw('unaccent(display_name) ilike unaccent(?)', ["%{$search}%"])
                    ->orWhereRaw('unaccent(type) ilike unaccent(?)', ["%{$search}%"]);
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
