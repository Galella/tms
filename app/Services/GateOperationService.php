<?php

namespace App\Services;

use App\Models\TruckMovement;
use App\Models\ActiveInventory;
use App\Models\Container;
use App\Services\ContainerValidationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GateOperationService
{
    protected $containerValidationService;

    public function __construct(ContainerValidationService $containerValidationService)
    {
        $this->containerValidationService = $containerValidationService;
    }

    /**
     * Process a Truck IN operation
     *
     * @param array $data
     * @return TruckMovement
     */
    public function processTruckIn($data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Validate container number format using ISO 6346
            $isValidFormat = $this->containerValidationService->isFormatValid($data['container_number']);
            if (!$isValidFormat) {
                throw new \Exception('Invalid container number format');
            }

            // 1b. Check if container exists in master container table
            $containerExists = $this->containerValidationService->containerExists($data['container_number']);
            if (!$containerExists) {
                throw new \Exception('Container does not exist in master container table');
            }

            // 2. Check if container is already active at the terminal
            if ($this->isDuplicateActive($data['terminal_id'], $data['container_number'])) {
                throw new \Exception('Container already active at this terminal');
            }

            // 3. Create the truck movement record
            $truckMovement = TruckMovement::create([
                'terminal_id' => $data['terminal_id'],
                'container_number' => $data['container_number'],
                'truck_number' => $data['truck_number'],
                'customer_id' => $data['customer_id'] ?? null,
                'shipping_line_id' => $data['shipping_line_id'] ?? null,
                'movement_type' => 'IN',
                'container_type' => $data['container_type'],
                'operation_type' => $data['operation_type'],
                'driver_name' => $data['driver_name'] ?? null,
                'chassis_number' => $data['chassis_number'] ?? null,
                'seal_number' => $data['seal_number'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'movement_time' => $data['movement_time'] ?? now(),
                'created_by' => Auth::id(),
            ]);

            // 4. Add to active inventory
            ActiveInventory::create([
                'terminal_id' => $data['terminal_id'],
                'container_number' => $data['container_number'],
                'customer_id' => $data['container_type'] === 'FULL' ? $data['customer_id'] : null,
                'shipping_line_id' => $data['container_type'] === 'EMPTY' ? $data['shipping_line_id'] : null,
                'status' => $data['container_type'],
                'block' => $data['block'],
                'row' => $data['row'],
                'tier' => $data['tier'],
                'date_in' => now()
            ]);

            return $truckMovement;
        });
    }

    /**
     * Process a Truck OUT operation
     * 
     * @param array $data
     * @return TruckMovement
     */
    public function processTruckOut($data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Verify that the container is currently active at this terminal
            $activeInventory = ActiveInventory::where('terminal_id', $data['terminal_id'])
                ->where('container_number', $data['container_number'])
                ->first();

            if (!$activeInventory) {
                throw new \Exception('Container not found in active inventory');
            }

            // 2. Create the truck movement record
            $truckMovement = TruckMovement::create([
                'terminal_id' => $data['terminal_id'],
                'container_number' => $data['container_number'],
                'truck_number' => $data['truck_number'],
                'customer_id' => $data['customer_id'] ?? null,
                'shipping_line_id' => $data['shipping_line_id'] ?? null,
                'movement_type' => 'OUT',
                'container_type' => $activeInventory->status, // Use existing status from inventory
                'operation_type' => $data['operation_type'],
                'driver_name' => $data['driver_name'] ?? null,
                'chassis_number' => $data['chassis_number'] ?? null,
                'seal_number' => $data['seal_number'] ?? null,
                'remarks' => $data['remarks'] ?? null,
                'movement_time' => $data['movement_time'] ?? now(),
                'created_by' => Auth::id(),
            ]);

            // 3. Remove from active inventory
            $activeInventory->delete();

            return $truckMovement;
        });
    }

    /**
     * Check if container is already active at terminal
     * 
     * @param int $terminalId
     * @param string $containerNumber
     * @return bool
     */
    private function isDuplicateActive($terminalId, $containerNumber)
    {
        return ActiveInventory::where('terminal_id', $terminalId)
            ->where('container_number', $containerNumber)
            ->exists();
    }
}