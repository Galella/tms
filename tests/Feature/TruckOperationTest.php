<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Terminal;
use App\Models\Container;
use App\Models\TruckMovement;
use App\Models\ActiveInventory;
use Illuminate\Support\Facades\Auth;

class TruckOperationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $terminal;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create();
        
        // Create a test terminal using the factory class directly
        $this->terminal = \Database\Factories\TerminalFactory::new()->create();
        
        // Attach the user to the terminal
        $this->user->terminals()->attach($this->terminal->id);
        
        // Refresh the user to get the relationship
        $this->user->refresh();
    }

    public function test_truck_in_operation_creates_movement_and_inventory(): void
    {
        // Create a valid container
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => 'ABCD1234567',
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        $data = [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'ABCD1234567',
            'truck_number' => 'B1234XYZ',
            'container_type' => 'FULL',
            'operation_type' => 'IMPORT',
            'block' => 'A',
            'row' => '01',
            'tier' => '02',
            'driver_name' => 'John Doe'
        ];

        // Authenticate as the user and disable middleware for testing
        $response = $this->withoutMiddleware()
             ->actingAs($this->user)
             ->post(route('truck-in.store'), $data);

        $response->assertRedirect(route('truck-in.index'));
        
        // Assertion: Truck movement record should be created
        $this->assertDatabaseHas('truck_movements', [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'ABCD1234567',
            'movement_type' => 'IN',
            'created_by' => $this->user->id,
        ]);

        // Assertion: Active inventory record should be created
        $this->assertDatabaseHas('active_inventory', [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'ABCD1234567',
            'status' => 'FULL',
            'block' => 'A',
            'row' => '01',
            'tier' => '02',
        ]);
    }

    public function test_truck_out_operation_removes_from_inventory(): void
    {
        // Create a valid container
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => 'EFGH7654321',
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        // First, create an active inventory record (simulating truck IN)
        ActiveInventory::create([
            'terminal_id' => $this->terminal->id,
            'container_number' => 'EFGH7654321',
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
            'container_number' => 'EFGH7654321',
            'truck_number' => 'B5678XYZ',
            'operation_type' => 'EXPORT',
            'driver_name' => 'Jane Smith'
        ];

        // Authenticate as the user and disable middleware for testing
        $response = $this->withoutMiddleware()
             ->actingAs($this->user)
             ->post(route('truck-out.store'), $data);

        $response->assertRedirect(route('truck-out.index'));
        
        // Assertion: Truck movement record should be created
        $this->assertDatabaseHas('truck_movements', [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'EFGH7654321',
            'movement_type' => 'OUT',
            'created_by' => $this->user->id,
        ]);

        // Assertion: Active inventory record should be removed
        $this->assertDatabaseMissing('active_inventory', [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'EFGH7654321',
        ]);
    }

    public function test_duplicate_container_at_terminal_is_rejected(): void
    {
        // Create a valid container
        $container = \Database\Factories\ContainerFactory::new()->create([
            'container_number' => 'IJKL1111111',
            'size' => '40',
            'type' => 'DRY',
            'ownership' => 'COC',
            'iso_code' => '45G1'
        ]);

        // First, create an active inventory record (simulating truck IN)
        ActiveInventory::create([
            'terminal_id' => $this->terminal->id,
            'container_number' => 'IJKL1111111',
            'customer_id' => null,
            'shipping_line_id' => null,
            'status' => 'FULL',
            'block' => 'C',
            'row' => '03',
            'tier' => '04',
            'date_in' => now()
        ]);

        // Try to do another truck IN with the same container at the same terminal
        $data = [
            'terminal_id' => $this->terminal->id,
            'container_number' => 'IJKL1111111',
            'truck_number' => 'B9999XYZ',
            'container_type' => 'FULL',
            'operation_type' => 'IMPORT',
            'block' => 'D',
            'row' => '04',
            'tier' => '05',
            'driver_name' => 'Test Driver'
        ];

        // Authenticate as the user and disable middleware for testing
        $response = $this->withoutMiddleware()
             ->actingAs($this->user)
             ->post(route('truck-in.store'), $data);

        // Assertion: Should return back with an error
        $response
            ->assertSessionHasErrors();
    }
}