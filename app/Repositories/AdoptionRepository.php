<?php

namespace App\Repositories;

use App\Models\Adoption;
use Illuminate\Database\Eloquent\Builder;

class AdoptionRepository extends Repository
{
    protected $table = 'adoptions';

    protected function getModelClass()
    {
        return Adoption::class;
    }

    public function getAdoptions(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'search');

        $query = $this->newQuery();

        $query
            ->with('animal', 'user')
            ->when($search, function(Builder $query, $search){
                $query
                    ->whereRaw('unaccent(description) ilike unaccent(?)', ["%{$search}%"]);
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
