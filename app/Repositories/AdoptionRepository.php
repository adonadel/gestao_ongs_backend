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
        $search = data_get($filters, 'name');

        $query = $this->newQuery();

        $query
            ->with('medias')
            ->when($search, function(Builder $query, $search){
                $query
                    ->where('name', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhere('tags', 'ilike', "%{$search}%");
            });

        if ($noPaginate) {
            return $query->get();
        }

        return $query->paginate();
    }
}
