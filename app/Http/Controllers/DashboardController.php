<?php

namespace App\Http\Controllers;

use App\Http\Services\Dashboard\QueryDashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getAnimalsTotal(Request $request)
    {
        $service = new QueryDashboardService();

        return $service->getAnimalsTotal($request->all());
    }
    public function getAnimalsTotalCastration(Request $request)
    {
        $service = new QueryDashboardService();

        return $service->getAnimalsTotalCastration($request->all());
    }
    public function getFinancesTotal(Request $request)
    {
        $service = new QueryDashboardService();

        return $service->getFinancesTotal($request->all());
    }
}
