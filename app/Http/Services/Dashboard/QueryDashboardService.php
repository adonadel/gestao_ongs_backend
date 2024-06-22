<?php

namespace App\Http\Services\Dashboard;

use App\Enums\AdoptionsStatusEnum;
use App\Enums\AnimalCastrateEnum;
use App\Enums\FinanceTypeEnum;
use App\Repositories\AnimalRepository;
use App\Repositories\FinanceRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueryDashboardService
{
    private $financeRepository;
    private $animalRepository;

    public function __construct()
    {
        $this->financeRepository = new FinanceRepository();
        $this->animalRepository = new AnimalRepository();
    }

    public function getAnimalsTotal(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $totalAnimals = $this->getAnimals($type);
        $totalAdopted = $this->getAnimalsAdopted($type);

        return [
            'total' => $totalAnimals,
            'totalAdopted' => $totalAdopted,
            'totalNotAdopted' => $totalAnimals - $totalAdopted
        ];
    }

    public function getAnimalsTotalCastration(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $totalCastrated = $this->getAnimalCastration($type, AnimalCastrateEnum::CASTRATED);
        $totalNotCastrated = $this->getAnimalCastration($type, AnimalCastrateEnum::NOT_CASTRATED);
        $totalAwaitingCastration = $this->getAnimalCastration($type, AnimalCastrateEnum::AWAITING_CASTRATION);

        return [
            'totalCastrated' => $totalCastrated,
            'totalNotCastrated' => $totalNotCastrated,
            'totalAwaitingCastration' => $totalAwaitingCastration
        ];
    }

    public function getFinancesTotal(array $filters)
    {
        $type = data_get($filters, 'type', 'yearly');

        $totalIncome = $this->getFinances($type, FinanceTypeEnum::INCOME);
        $totalExpense = $this->getFinances($type, FinanceTypeEnum::EXPENSE);
        $expensesToChart = $this->getFinancesToChart($type, FinanceTypeEnum::EXPENSE);
        $incomesToChart = $this->getFinancesToChart($type, FinanceTypeEnum::INCOME);

        $incomesCount = count($incomesToChart);
        $expensesCount = count($expensesToChart);

        [$expensesToChart, $incomesToChart] = $this->equalizeFinances(
            $incomesCount,
            $expensesCount,
            $expensesToChart,
            $incomesToChart
        );

        return [
            'total' => $totalIncome - $totalExpense,
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'chart' => [
                [
                    'name' => 'Entradas',
                    'type' => 'line',
                    'data' => $incomesToChart,
                    'lineStyle' => [
                        'color' => '#15b6b1'
                    ],
                    'itemStyle' => [
                        'color' => '#15b6b1'
                    ],
                    'footerData' => $type === 'all' ? $this->getYears() : [],
                ],
                [
                    'name' => 'Saídas',
                    'type' => 'line',
                    'data' => $expensesToChart,
                    'lineStyle' => [
                        'color' => '#ff6868'
                    ],
                    'itemStyle' => [
                        'color' => '#ff6868'
                    ],
                    'footerData' => $type === 'all' ? $this->getYears() : [],
                ],
            ]
        ];
    }

    private function getFinancesToChart(string $filterType, FinanceTypeEnum $financeType)
    {
        $data = [];

        if ($filterType === 'yearly') {
            $finances = $this->financeRepository->newQuery()
                ->select(DB::raw('EXTRACT(month FROM date) as month'), DB::raw('SUM(value) as total'))
                ->where('type', $financeType)
                ->whereYear('date', now()->year)
                ->groupBy('month')
                ->orderBy('month')
                ->pluck('total', 'month')
                ->toArray();

            for ($i = 1; $i <= max(array_keys($finances)); $i++) {
                if (!isset($finances[$i])) {
                    $finances[$i] = 0;
                }
            }

            ksort($finances);

            foreach ($finances as $i => $value) {
                $data[$i] = $value === 0 ? $value : (float) $value;
            }
        }

        if ($filterType === 'monthly') {
            $finances = $this->financeRepository->newQuery()
                ->select(
                    DB::raw('EXTRACT(WEEK FROM date) as week'),
                    DB::raw('SUM(value) as total')
                )
                ->where('type', $financeType)
                ->whereYear('date', now()->year)
                ->whereMonth('date', now()->month)
                ->groupBy(DB::raw('EXTRACT(YEAR FROM date)'), DB::raw('EXTRACT(WEEK FROM date)'))
                ->pluck('total', 'week')
                ->toArray();

            $weeks = $this->getMonthWeeks();
            foreach ($weeks as $week) {
                if (!isset($finances[$week]) && $week < array_key_first($finances)) {
                    $finances[$week] = 0;
                }
            }

            ksort($finances);

            foreach ($finances as $i => $value) {
                $data[$i] = $value === 0 ? $value : (float) $value;
            }
        }

        if ($filterType === 'weekly') {
            $finances = $this->financeRepository->newQuery()
                ->selectRaw('SUM(value) AS total, EXTRACT(dow FROM date) AS dayofweek')
                ->where('type', $financeType)
                ->whereBetween('date', [Carbon::now()->subDays(7), Carbon::now()])
                ->groupBy('dayofweek')
                ->orderBy('dayofweek')
                ->pluck('total', 'dayofweek')
                ->toArray();

            $data = $this->handleWeeklyFill($finances);

        }

        if ($filterType === 'all'){
            $data = $this->financeRepository->newQuery()
                ->selectRaw('SUM(value) AS total, EXTRACT(year FROM date) AS year')
                ->where('type', $financeType)
                ->groupBy('year')
                ->orderBy('year')
                ->pluck('total', 'year')
                ->toArray();

            $years = $this->getYears();

            foreach ($years as $year) {
                if(!isset($data[$year])) {
                    $data[$year] = 0;
                }
            }

            ksort($data);
        }

        return array_values($data);
    }

    private function getFinances(string $filterType, FinanceTypeEnum $financeType)
    {
        $finances = $this->financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->when($filterType === 'yearly', function ($query) {
                $query
                    ->whereYear('date', now()->year);
            })
            ->when($filterType === 'monthly', function ($query) {
                $query
                    ->whereYear('date', now()->year)
                    ->whereMonth('date', now()->month);
            })
            ->when($filterType === 'weekly', function ($query) {
                $query
                    ->whereBetween('date', [Carbon::now()->subDays(6)->startOfDay(), Carbon::now()->endOfDay()]);
            })
            ->where('type', $financeType)
            ->sum('value');

        return $finances;
    }

    private function equalizeFinances(int $incomesCount, int $expensesCount, ?array $expensesToChart, ?array $incomesToChart): array
    {
        if ($incomesCount !== $expensesCount) {
            if ($incomesCount > $expensesCount) {
                $expensesToChart = array_merge($expensesToChart, array_fill(0, $incomesCount - $expensesCount, 0));
            } else {
                $incomesToChart = array_merge($incomesToChart, array_fill(0, $expensesCount - $incomesCount, 0));
            }
        }
        ksort($incomesToChart);
        ksort($expensesToChart);

        return [$expensesToChart, $incomesToChart];
    }

    private function getMonthWeeks(): array
    {
        $now = Carbon::now();
        $currentMonth = $now->month;
        $currentYear = $now->year;

        $firstDayOfMonth = Carbon::createFromDate($currentYear, $currentMonth, 1);
        $lastDayOfMonth = $firstDayOfMonth->copy()->endOfMonth();

        $weeks = [];

        while ($firstDayOfMonth <= $lastDayOfMonth) {
            $weekStartDate = $firstDayOfMonth->copy();
            $weekEndDate = $weekStartDate->copy()->addDays(6);

            $weeks[] = [
                'start_date' => $weekStartDate->format('Y-m-d'),
                'end_date' => $weekEndDate->format('Y-m-d'),
            ];

            $firstDayOfMonth->addDays(7);
        }

        $weekNumbers = [];

        foreach ($weeks as $week) {
            $weekNumber = Carbon::parse($week['start_date'])->isoWeek();
            $weekNumbers[] = $weekNumber;
        }

        return $weekNumbers;
    }

    private function handleWeeklyFill(array $finances)
    {
        $daysOfWeek = range(0, 6);
        $filledArray = [];

        foreach ($daysOfWeek as $dayOfWeek) {
            $foundValue = null;
            foreach ($finances as $key => $data) {
                if ($key === $dayOfWeek) {
                    $foundValue = $data;
                    break;
                }
            }

            $filledArray[$dayOfWeek] = $foundValue ? (float) $foundValue : 0;
        }

        return $filledArray;
    }

    private function getAnimals(string $filterType)
    {
        return $this->animalRepository->newQuery()
            ->when($filterType === 'yearly', function ($query) {
                return $query->whereYear('created_at', now()->year);
            })
            ->when($filterType === 'monthly', function ($query) {
                return $query->whereMonth('created_at', now()->month);
            })
            ->when($filterType === 'weekly', function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
            })
            ->count();
    }

    private function getAnimalsAdopted(string $filterType)
    {
        return $this->animalRepository->newQuery()
            ->join('adoptions', 'animals.id', '=', 'adoptions.animal_id')
            ->when($filterType === 'yearly', function ($query) {
                return $query->whereYear('animals.created_at', now()->year);
            })
            ->when($filterType === 'monthly', function ($query) {
                return $query->whereMonth('animals.created_at', now()->month);
            })
            ->when($filterType === 'weekly', function ($query) {
                return $query->whereBetween('animals.created_at', [Carbon::now()->subDays(7), Carbon::now()]);
            })
            ->where('adoptions.status', AdoptionsStatusEnum::ADOPTED)
            ->count();
    }

    private function getAnimalCastration(string $filterType, AnimalCastrateEnum $castrationType)
    {
        return $this->animalRepository->newQuery()
            ->where('castrate_type', $castrationType)
            ->when($filterType === 'yearly', function ($query) {
                return $query->whereYear('created_at', now()->year);
            })
            ->when($filterType === 'monthly', function ($query) {
                return $query->whereMonth('created_at', now()->month);
            })
            ->when($filterType === 'weekly', function ($query) {
                return $query->whereBetween('created_at', [Carbon::now()->subDays(7), Carbon::now()]);
            })
            ->count();
    }

    private function getYears()
    {
        return $this->financeRepository->newQuery()
            ->selectRaw('EXTRACT(year FROM date) as year')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('year')
            ->toArray();
    }
}
