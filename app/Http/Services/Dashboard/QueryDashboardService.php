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
    private $initial;
    private $final;
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

        $this->handleType($type);

        $totalAnimals = $this->animalRepository->newQuery()
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalAdopted = $this->animalRepository->newQuery()
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

        $totalCastrated = $this->animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::CASTRATED)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalNotCastrated = $this->animalRepository->newQuery()
            ->where('castrate_type', AnimalCastrateEnum::NOT_CASTRATED)
            ->when($this->initial && $this->final, function ($query){
                $query->whereBetween('created_at', [$this->initial, $this->final]);
            })
            ->count();

        $totalAwaitingCastration = $this->animalRepository->newQuery()
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

        $totalIncome = $this->financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::INCOME)
            ->first();

        $totalExpense = $this->financeRepository->newQuery()
            ->select(DB::raw('SUM(value) as total'))
            ->where('type', FinanceTypeEnum::EXPENSE)
            ->first();

        $expensesToChart = $this->getFinancesToChart($type, FinanceTypeEnum::EXPENSE);
        $incomesToChart = $this->getFinancesToChart($type, FinanceTypeEnum::INCOME);

        $incomesCount = count($incomesToChart);
        $expensesCount = count($expensesToChart);

        [$expensesToChart, $incomesToChart] = $this->equalizeFinances(
            $type,
            $incomesCount,
            $expensesCount,
            $expensesToChart,
            $incomesToChart
        );

        return [
            'total' => $totalIncome->total - $totalExpense->total,
            'totalIncome' => $totalIncome->total,
            'totalExpense' => $totalExpense->total,
            'chart' => [
                [
                    'name' => 'Entradas',
                    'type' => 'line',
                    'stack'=> 'Total',
                    'data' => $expensesToChart,
                    'lineStyle' => [
                        'color' => '#ff6868'
                    ],
                    'itemStyle' => [
                        'color' => '#ff6868'
                    ]
                ],
                [
                    'name' => 'Saídas',
                    'type' => 'line',
                    'stack'=> 'Total',
                    'data' => $incomesToChart,
                    'lineStyle' => [
                        'color' => '#15b6b1'
                    ],
                    'itemStyle' => [
                        'color' => '#15b6b1'
                    ]
                ],
            ]
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

    private function getFinancesToChart(string $filterType, FinanceTypeEnum $financeType)
    {
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

            $runningTotal = 0;
            $data = [];

            foreach ($finances as $i => $value) {
                $runningTotal += $value;
                $data[$i] = $runningTotal;
            }

            for ($i = 1; $i <= max(array_keys($finances)); $i++) {
                if (!isset($finances[$i])) {
                    $finances[$i] = 0;
                }
            }

            return array_values($data);
        }

        if ($filterType === 'monthly') {
            $finances = $this->financeRepository->newQuery()
                ->select(
                    DB::raw('EXTRACT(WEEK FROM date) as week'),
                    DB::raw('SUM(value) as total')
                )
                ->where('type', $financeType)
                ->whereMonth('date', now()->month)
                ->groupBy(DB::raw('EXTRACT(YEAR FROM date)'), DB::raw('EXTRACT(WEEK FROM date)'))
                ->pluck('total', 'week')
                ->toArray();

            $weeks = $this->getMonthWeeks();

            foreach ($weeks as  $week) {
                if (!isset($finances[$week])) {
                    $finances[$week] = 0;
                }
            }

            ksort($finances);

            $runningTotal = 0;
            $data = [];

            foreach ($finances as $i => $value) {
                $runningTotal += $value;
                $data[$i] = $runningTotal;
            }

            return array_values($data);
        }
    }

    private function equalizeFinances(string $filterType, int $incomesCount, int $expensesCount, ?array $expensesToChart, ?array $incomesToChart): array
    {
        if ($filterType === 'yearly') {
            if ($incomesCount !== $expensesCount) {
                if ($incomesCount > $expensesCount) {
                    $lastExpense = $expensesToChart[$expensesCount - 1];
                    $expensesToChart = array_merge($expensesToChart, array_fill(0, $incomesCount - $expensesCount, $lastExpense));
                } else {
                    $lastIncome = $incomesToChart[$incomesCount - 1];
                    $incomesToChart = array_merge($incomesToChart, array_fill(0, $expensesCount - $incomesCount, $lastIncome));
                }
            }
        }

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
            $weekStartDate = $firstDayOfMonth->copy(); // Create a copy of the first day of the week
            $weekEndDate = $weekStartDate->copy()->addDays(6); // Calculate the last day of the week

            $weeks[] = [
                'start_date' => $weekStartDate->format('Y-m-d'),
                'end_date' => $weekEndDate->format('Y-m-d'),
            ];

            $firstDayOfMonth->addDays(7); // Increment the first day of the week to the next week
        }

        $weekNumbers = []; // Initialize an empty array to store week numbers

        foreach ($weeks as $week) {
            $weekNumber = Carbon::parse($week['start_date'])->isoWeek(); // Extract week number from start date
            $weekNumbers[] = $weekNumber;
        }

        return $weekNumbers;
    }
}
