<?php

namespace App\Http\Controllers;

use App\Models\ActiveInventory;
use App\Models\TruckMovement;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view with key statistics
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Calculate average dwell days for active inventory
        $activeInventoryAvgDwellDays = ActiveInventory::avg('dwell_days');

        // Get counts for other dashboard stats
        $truckInTodayCount = TruckMovement::where('movement_type', 'IN')
            ->whereDate('movement_time', today())
            ->count();

        $truckOutTodayCount = TruckMovement::where('movement_type', 'OUT')
            ->whereDate('movement_time', today())
            ->count();

        $activeContainerCount = ActiveInventory::count();

        return view('dashboard.index', [
            'activeInventoryAvgDwellDays' => $activeInventoryAvgDwellDays,
            'truckInTodayCount' => $truckInTodayCount,
            'truckOutTodayCount' => $truckOutTodayCount,
            'activeContainerCount' => $activeContainerCount,
        ]);
    }
}
