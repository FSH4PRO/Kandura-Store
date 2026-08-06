<?php

namespace App\Http\Controllers\dashboard;
use App\Http\Controllers\Controller;
use App\Services\Admin\DashboardService;

class Analytics extends Controller
{
    public function __construct(private DashboardService $service) {}

    public function index()
    {
        $data = $this->service->getData();
        return view('content.dashboard.dashboards-analytics', $data);
    }
}