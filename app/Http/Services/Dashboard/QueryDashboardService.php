<?php

namespace App\Http\Services\Dashboard;

use App\Enums\AdoptionsStatusEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\FinanceTypeEnum;
use App\Repositories\AnimalRepository;
use App\Repositories\FinanceRepository;
use Illuminate\Support\Facades\DB;

class QueryDashboardService
{
    private $initial;
    private $final;

    public function getAnimalsTotal(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $this->handleType($type);

        $animalRepository = new AnimalRepository();

        $totalAnimals = $animalRepository->newQuery()
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalAdopted = $animalRepository->newQuery()
            ->join('adoptions', 'animals.id', '=', 'adoptions.animal_id')
            ->where('adoptions.status', AdoptionsStatusEnum::ADOPTED)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('animals.created_at', [$this->initial, $this->final]);
            })
            ->count();

        return [
            'total' => $totalAnimals,
            'totalAdopted' => $totalAdopted,
            'totalNotAdopted' => $totalAnimals - $totalAdopted
        ];
    }

    public function getAnimalsTotalCastration(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $this->handleType($type);

        $animalRepository = new AnimalRepository();

        $totalCastrated = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::CASTRATED)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalNotCastrated = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::NOT_CASTRATED)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalAwaitingCastration = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::AWAITING_CASTRATION)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        return [
            'totalCastrated' => $totalCastrated,
            'totalNotCastrated' => $totalNotCastrated,
            'totalAwaitingCastration' => $totalAwaitingCastration
        ];
    }

    public function getFinancesTotal(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $this->handleType($type);

        $financeRepository = new FinanceRepository();

        $totalIncome = $financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::INCOME)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->first();

        $totalExpense = $financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::EXPENSE)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->first();

        return [
            'total' => $totalIncome->total - $totalExpense->total,
            'totalIncome' => $totalIncome->total,
            'totalExpense' => $totalExpense->total,
        ];
    }

    private function handleType(string $type)
    {
        switch ($type) {
            case 'yearly':
                $this->initial = now()->year.'-01-01';
                $this->final = now()->year.'-12-31';
                break;
            case 'monthly':
                $this->initial = now()->month.'-01-01';
                $this->final = now()->month.'-12-'.now()->month( now()->month)->daysInMonth;
                break;
            case 'weekly':
                $this->initial = now()->subDays(7)->format('Y-m-d');
                $this->final = now()->format('Y-m-d');
                break;
            case 'all':
                $this->initial = null;
                $this->final = null;
        }
    }
}
