<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GateOperationService;
use Illuminate\Support\Facades\Auth;

class TruckInController extends Controller
{
    protected $gateOperationService;

    public function __construct(GateOperationService $gateOperationService)
    {
        $this->gateOperationService = $gateOperationService;
        $this->middleware('auth');
        $this->middleware('terminal.access');
    }

    /**
     * Show the form for creating a new truck IN record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('truck-in.create');
    }

    /**
     * Store a newly created truck IN record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'terminal_id' => 'required|exists:terminals,id',
            'container_number' => 'required|string|size:11',
            'truck_number' => 'required|string|max:20',
            'container_type' => 'required|in:FULL,EMPTY',
            'operation_type' => 'required|in:EXPORT,IMPORT,STUFFING,RESTUFFING,GATE',
            'block' => 'required|string|max:10',
            'row' => 'required|string|max:10',
            'tier' => 'required|string|max:10',
        ]);

        try {
            $data = $request->only([
                'terminal_id',
                'container_number',
                'truck_number',
                'customer_id',
                'shipping_line_id',
                'container_type',
                'operation_type',
                'driver_name',
                'chassis_number',
                'seal_number',
                'remarks',
                'block',
                'row',
                'tier',
                'movement_time'
            ]);

            $this->gateOperationService->processTruckIn($data);

            return redirect()->route('truck-in.index')
                ->with('success', 'Truck IN operation recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display a listing of the truck IN records.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $truckMovements = \App\Models\TruckMovement::where('movement_type', 'IN')
            ->where('created_by', Auth::id())
            ->paginate(10);

        return view('truck-in.index', compact('truckMovements'));
    }
}
