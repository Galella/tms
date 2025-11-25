<?php

namespace App\Http\Controllers;

use App\Models\TruckMovement;
use App\Models\ActiveInventory;
use App\Models\Terminal;
use App\Models\Customer;
use App\Models\ShippingLine;
use App\Services\Iso6346Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TruckInController extends Controller
{
    protected $validator;

    public function __construct(Iso6346Validator $validator)
    {
        $this->validator = $validator;
        $this->middleware('auth');
        $this->middleware('terminal.access');
    }

    /**
     * Display a listing of the truck IN records.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $truckMovements = TruckMovement::with(['terminal', 'customer', 'shippingLine'])
            ->where('movement_type', 'IN')
            ->where('created_by', Auth::id())
            ->latest()
            ->paginate(10);

        return view('truck-in.index', compact('truckMovements'));
    }

    /**
     * Show the form for creating a new truck IN record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Get terminals accessible to the user
        $terminals = Auth::user()->terminals;
        
        // Get any other required data for the form
        $customers = Customer::all();
        $shippingLines = ShippingLine::all();
        
        return view('truck-in.create', compact('terminals', 'customers', 'shippingLines'));
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
            'customer_id' => 'nullable|exists:customers,id',
            'shipping_line_id' => 'nullable|exists:shipping_lines,id',
            'container_type' => 'required|in:FULL,EMPTY',
            'operation_type' => 'required|in:EXPORT,IMPORT,STUFFING,RESTUFFING,GATE',
            'driver_name' => 'nullable|string|max:100',
            'chassis_number' => 'nullable|string|max:20',
            'seal_number' => 'nullable|string|max:20',
            'remarks' => 'nullable|string',
            'block' => 'required|string|max:10',
            'row' => 'required|string|max:10',
            'tier' => 'required|string|max:10',
            'movement_time' => 'required|date',
        ]);

        try {
            // Validate container number using ISO 6346
            $isValidFormat = $this->validator->validate($request->container_number);
            if (!$isValidFormat) {
                throw new \Exception('Invalid container number format according to ISO 6346 standard.');
            }

            // Check if container already exists in active inventory at this terminal
            $duplicateExists = ActiveInventory::where('terminal_id', $request->terminal_id)
                ->where('container_number', $request->container_number)
                ->exists();

            if ($duplicateExists) {
                throw new \Exception('Container already exists in active inventory at this terminal.');
            }

            // Process the truck IN operation
            $truckMovement = DB::transaction(function () use ($request) {
                // Create truck movement record
                $movement = TruckMovement::create([
                    'terminal_id' => $request->terminal_id,
                    'container_number' => $request->container_number,
                    'truck_number' => $request->truck_number,
                    'customer_id' => $request->customer_id,
                    'shipping_line_id' => $request->shipping_line_id,
                    'movement_type' => 'IN',
                    'container_type' => $request->container_type,
                    'operation_type' => $request->operation_type,
                    'driver_name' => $request->driver_name,
                    'chassis_number' => $request->chassis_number,
                    'seal_number' => $request->seal_number,
                    'remarks' => $request->remarks,
                    'movement_time' => $request->movement_time,
                    'created_by' => Auth::id(),
                ]);

                // Add to active inventory
                ActiveInventory::create([
                    'terminal_id' => $request->terminal_id,
                    'container_number' => $request->container_number,
                    'customer_id' => $request->customer_id,
                    'shipping_line_id' => $request->shipping_line_id,
                    'status' => $request->container_type,
                    'block' => $request->block,
                    'row' => $request->row,
                    'tier' => $request->tier,
                    'date_in' => now(),
                ]);

                return $movement;
            });

            return redirect()->route('truck-in.index')
                ->with('success', 'Truck IN operation recorded successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
}