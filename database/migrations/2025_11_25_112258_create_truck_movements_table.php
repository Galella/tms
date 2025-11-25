<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('truck_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terminal_id')->nullable(false);
            $table->string('container_number', 11)->nullable(false);
            $table->string('truck_number', 20)->nullable(false);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('shipping_line_id')->nullable();
            $table->enum('movement_type', ['IN', 'OUT'])->nullable(false);
            $table->enum('container_type', ['FULL', 'EMPTY'])->nullable(false);
            $table->enum('operation_type', ['EXPORT', 'IMPORT', 'STUFFING', 'RESTUFFING', 'GATE'])->nullable(false);
            $table->string('driver_name', 100)->nullable();
            $table->string('chassis_number', 20)->nullable();
            $table->string('seal_number', 20)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('movement_time')->nullable(false);
            $table->unsignedBigInteger('created_by')->nullable(false);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('terminal_id')->references('id')->on('terminals');
            $table->foreign('container_number')->references('container_number')->on('containers');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('truck_movements');
    }
};
