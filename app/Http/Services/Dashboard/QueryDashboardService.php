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
    public function getAnimalsTotal(array $filters)
    {
        $initial = data_get($filters, 'initial');
        $final = data_get($filters, 'final');

        $animalRepository = new AnimalRepository();

        $totalAnimals = $animalRepository->newQuery()
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
            })
            ->count();

        $totalAdopted = $animalRepository->newQuery()
            ->leftJoin('adoptions', 'animals.id', '=', 'adoptions.animal_id')
            ->where('adoptions.status', AdoptionsStatusEnum::ADOPTED)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('animal.created_at', [$initial, $final]);
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
        $initial = data_get($filters, 'initial');
        $final = data_get($filters, 'final');

        $animalRepository = new AnimalRepository();

        $totalCastrated = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::CASTRATED)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
            })
            ->count();

        $totalNotCastrated = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::NOT_CASTRATED)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
            })
            ->count();

        $totalAwaitingCastration = $animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::AWAITING_CASTRATION)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
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
        $initial = data_get($filters, 'initial');
        $final = data_get($filters, 'final');

        $financeRepository = new FinanceRepository();

        $totalIncome = $financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::INCOME)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
            })
            ->first();

        $totalExpense = $financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::EXPENSE)
            ->when($initial && $final, function ($query) use ($initial, $final) {
                $query->whereBetween('created_at', [$initial, $final]);
            })
            ->first();

        return [
            'total' => $totalIncome->total - $totalExpense->total,
            'totalIncome' => $totalIncome->total,
            'totalExpense' => $totalExpense->total,
        ];
    }
}
