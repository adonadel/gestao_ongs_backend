<?php

namespace App\Repositories;

use App\Models\Finance;
use Illuminate\Database\Eloquent\Builder;

class FinanceRepository extends Repository
{
    protected $table = 'finances';

    protected function getModelClass()
    {
        return Finance::class;
    }

    public function getFinances(array $filters)
    {
        $noPaginate = data_get($filters, 'no-paginate', false);
        $search = data_get($filters, 'search');

        $query = $this->newQuery();

        $query
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
