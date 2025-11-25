<?php

namespace App\Http\Controllers;

use App\Models\ActiveInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActiveInventoryController extends Controller
{
    public function __construct()
    {
        // Middleware is handled via routes
    }

    /**
     * Display a listing of the active inventory.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $activeInventory = ActiveInventory::with(['terminal', 'container', 'customer', 'shippingLine'])->paginate(10);

        return view('active-inventory.index', compact('activeInventory'));
    }

    /**
     * Show the form for creating a new active inventory record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // If needed, implement create functionality
        abort(404, 'Not implemented');
    }

    /**
     * Store a newly created active inventory record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // If needed, implement store functionality
        abort(404, 'Not implemented');
    }

    /**
     * Display the specified active inventory record.
     *
     * @param  \App\Models\ActiveInventory  $activeInventory
     * @return \Illuminate\View\View
     */
    public function show(ActiveInventory $activeInventory)
    {
        return view('active-inventory.show', compact('activeInventory'));
    }

    /**
     * Show the form for editing the specified active inventory record.
     *
     * @param  \App\Models\ActiveInventory  $activeInventory
     * @return \Illuminate\View\View
     */
    public function edit(ActiveInventory $activeInventory)
    {
        // If needed, implement edit functionality
        abort(404, 'Not implemented');
    }

    /**
     * Update the specified active inventory record in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ActiveInventory  $activeInventory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ActiveInventory $activeInventory)
    {
        // If needed, implement update functionality
        abort(404, 'Not implemented');
    }

    /**
     * Remove the specified active inventory record from storage.
     *
     * @param  \App\Models\ActiveInventory  $activeInventory
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ActiveInventory $activeInventory)
    {
        // If needed, implement destroy functionality
        abort(404, 'Not implemented');
    }
}
