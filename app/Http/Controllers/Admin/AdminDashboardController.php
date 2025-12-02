<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminMetricsService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(private AdminMetricsService $metrics)
    {
    }

    public function overview(Request $request)
    {
        $days = (int) $request->query('days', 30);

        return response()->json($this->metrics->overview($days));
    }

    public function addons(Request $request)
    {
        $days = (int) $request->query('days', 30);

        return response()->json($this->metrics->addonDashboard($days));
    }
}

