<?php

namespace App\Http\Controllers;

use App\Models\Container;
use App\Models\Terminal;
use App\Models\TruckMovement;
use App\Services\ContainerValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContainerController extends Controller
{
    protected $containerValidationService;

    public function __construct(ContainerValidationService $containerValidationService)
    {
        $this->containerValidationService = $containerValidationService;
        $this->middleware('auth');
        $this->middleware('permission:manage-containers')->except(['index', 'show']);
    }

    /**
     * Display a listing of the containers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Container::query();
        
        // Apply search filters
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('container_number', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%")
                  ->orWhere('ownership', 'LIKE', "%{$search}%")
                  ->orWhere('iso_code', 'LIKE', "%{$search}%");
            });
        }

        // Filter by size
        if ($request->has('size') && !empty($request->size)) {
            $query->where('size', $request->size);
        }

        // Filter by ownership
        if ($request->has('ownership') && !empty($request->ownership)) {
            $query->where('ownership', $request->ownership);
        }

        $containers = $query->with(['terminals'])->paginate(10);
        $containers->appends($request->query());

        // Get terminals for filter dropdown
        $terminals = Terminal::all();

        return view('containers.index', compact('containers', 'terminals'));
    }

    /**
     * Show the form for creating a new container.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $terminals = Terminal::all();

        return view('containers.create', compact('terminals'));
    }

    /**
     * Store a newly created container in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'container_number' => 'required|string|size:11|unique:containers,container_number',
            'size' => 'required|in:20,40,45',
            'type' => 'required|string|max:50',
            'ownership' => 'required|in:COC,SOC,FU',
            'iso_code' => 'required|string|size:4',
            'terminal_ids' => 'array',
            'terminal_ids.*' => 'exists:terminals,id',
        ]);

        // Validate ISO 6346 format
        if (!$this->containerValidationService->validateContainerNumber($request->container_number)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['container_number' => 'Invalid container number format according to ISO 6346 standard.']);
        }

        $container = Container::create($request->only([
            'container_number',
            'size',
            'type',
            'ownership',
            'iso_code'
        ]));

        if ($request->filled('terminal_ids')) {
            $container->terminals()->attach($request->terminal_ids);
        }

        return redirect()->route('containers.index')
            ->with('success', 'Container created successfully.');
    }

    /**
     * Display the specified container.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\View\View
     */
    public function show(Container $container)
    {
        $container->load(['terminals', 'activeInventories']);

        return view('containers.show', compact('container'));
    }

    /**
     * Show the form for editing the specified container.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\View\View
     */
    public function edit(Container $container)
    {
        $terminals = Terminal::all();

        return view('containers.edit', compact('container', 'terminals'));
    }

    /**
     * Update the specified container in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Container $container)
    {
        $request->validate([
            'size' => 'required|in:20,40,45',
            'type' => 'required|string|max:50',
            'ownership' => 'required|in:COC,SOC,FU',
            'iso_code' => 'required|string|size:4',
            'terminal_ids' => 'array',
            'terminal_ids.*' => 'exists:terminals,id',
        ]);

        $container->update($request->only([
            'size',
            'type',
            'ownership',
            'iso_code'
        ]));

        // Sync terminals
        if ($request->has('terminal_ids')) {
            $container->terminals()->sync($request->terminal_ids);
        } else {
            $container->terminals()->detach();
        }

        return redirect()->route('containers.index')
            ->with('success', 'Container updated successfully.');
    }

    /**
     * Remove the specified container from storage.
     *
     * @param  \App\Models\Container  $container
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Container $container)
    {
        // Check if container is currently in active inventory/truck movements
        $isActive = TruckMovement::where('container_number', $container->container_number)
            ->whereNull('out_time')
            ->exists();
            
        if ($isActive) {
            return redirect()->route('containers.index')
                ->with('error', 'Cannot delete container. It is currently in active movement.');
        }

        $container->terminals()->detach();
        $container->delete();

        return redirect()->route('containers.index')
            ->with('success', 'Container deleted successfully.');
    }
}