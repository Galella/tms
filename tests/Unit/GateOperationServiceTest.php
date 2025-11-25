<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Terminal;
use App\Models\Container;
use App\Models\ActiveInventory;
use App\Services\GateOperationService;
use App\Services\ContainerValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class GateOperationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $terminal;
    protected $service;
    protected $containerValidationService;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create services
        $this->containerValidationService = new ContainerValidationService();
        $this->service = new GateOperationService($this->containerValidationService);
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Create a test terminal
        $this->terminal = \Database\Factories\TerminalFactory::new()->create();
        
        // Attach the user to the terminal
        $this->user->terminals()->attach($this->terminal->id);
        
        // Refresh the user to get the relationship
        $this->user->refresh();
        
        // Log in the user for the tests
        $this->actingAs($this->user);
    }

    public function test_process_truck_in_creates_movement_and_inventory(): void
    {
        // Create a valid container with a specific, known valid container number
        // Using CSQU3054383 which should be a valid container number
        $validContainerNumber = 'CSQU3054383'; // Valid container number with check digit 3

        // Create container in the database
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => $validContainerNumber,
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        $data = [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'truck_number' => 'B1234XYZ',
            'container_type' => 'FULL',
            'operation_type' => 'IMPORT',
            'block' => 'A',
            'row' => '01',
            'tier' => '02',
            'driver_name' => 'John Doe'
        ];

        // Process the truck IN operation
        $movement = $this->service->processTruckIn($data);

        // Assertions
        $this->assertNotNull($movement);
        $this->assertEquals('IN', $movement->movement_type);
        $this->assertEquals($validContainerNumber, $movement->container_number);

        // Check if the movement was saved in the database
        $this->assertDatabaseHas('truck_movements', [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'movement_type' => 'IN',
            'created_by' => $this->user->id,
        ]);

        // Check if the inventory was created
        $this->assertDatabaseHas('active_inventory', [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'status' => 'FULL',
            'block' => 'A',
            'row' => '01',
            'tier' => '02',
        ]);
    }

    public function test_process_truck_out_removes_from_inventory(): void
    {
        // Using a known valid container number for the test
        $validContainerNumber = 'CSQU3054383'; // Valid container number

        // Create container in the database
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => $validContainerNumber,
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        // First, create an active inventory record (simulating truck IN)
        ActiveInventory::create([
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'customer_id' => null,
            'shipping_line_id' => null,
            'status' => 'EMPTY',
            'block' => 'B',
            'row' => '02',
            'tier' => '03',
            'date_in' => now()
        ]);

        $data = [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'truck_number' => 'B5678XYZ',
            'operation_type' => 'EXPORT',
            'driver_name' => 'Jane Smith'
        ];

        // Process the truck OUT operation
        $movement = $this->service->processTruckOut($data);

        // Assertions
        $this->assertNotNull($movement);
        $this->assertEquals('OUT', $movement->movement_type);
        $this->assertEquals($validContainerNumber, $movement->container_number);

        // Check if the movement was saved in the database
        $this->assertDatabaseHas('truck_movements', [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'movement_type' => 'OUT',
            'created_by' => $this->user->id,
        ]);

        // Check if the inventory was removed
        $this->assertDatabaseMissing('active_inventory', [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
        ]);
    }

    public function test_duplicate_container_at_terminal_is_rejected(): void
    {
        // Using a known valid container number for the test
        $validContainerNumber = 'CSQU3054383'; // Valid container number

        // Create container in the database
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => $validContainerNumber,
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        // First, create an active inventory record (simulating truck IN)
        ActiveInventory::create([
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'customer_id' => null,
            'shipping_line_id' => null,
            'status' => 'FULL',
            'block' => 'C',
            'row' => '03',
            'tier' => '04',
            'date_in' => now()
        ]);

        $data = [
            'terminal_id' => $this->terminal->id,
            'container_number' => $validContainerNumber,
            'truck_number' => 'B9999XYZ',
            'container_type' => 'FULL',
            'operation_type' => 'IMPORT',
            'block' => 'D',
            'row' => '04',
            'tier' => '05',
            'driver_name' => 'Test Driver'
        ];

        // Expect an exception when trying to process truck IN with duplicate active container
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Container already active at this terminal');

        $this->service->processTruckIn($data);
    }
}