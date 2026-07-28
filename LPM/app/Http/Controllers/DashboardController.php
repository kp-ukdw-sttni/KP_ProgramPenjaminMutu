<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboardService
    ) {}

    public function index(Request $request)
    {
        $kpi            = $this->dashboardService->getKpiCards();
        $pppepData      = $this->dashboardService->getPppepCycleData();
        $openKtsByProdi = $this->dashboardService->getOpenKtsByProdi();
        $standardsMet   = $this->dashboardService->getStandardsMetByProdi();
        $recentFindings = $this->dashboardService->getRecentFindings();

        return view('dashboard.index', compact(
            'kpi',
            'pppepData',
            'openKtsByProdi',
            'standardsMet',
            'recentFindings'
        ));
    }
}
