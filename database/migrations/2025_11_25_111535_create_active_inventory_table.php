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
        Schema::create('active_inventory', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terminal_id')->nullable(false);
            $table->string('container_number', 11)->nullable(false);
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('shipping_line_id')->nullable();
            $table->enum('status', ['FULL', 'EMPTY'])->nullable(false);
            $table->string('block', 10)->nullable(false);
            $table->string('row', 10)->nullable(false);
            $table->string('tier', 10)->nullable(false);
            $table->dateTime('date_in')->nullable(false);
            $table->timestamps();

            // Create unique key on terminal_id and container_number
            $table->unique(['terminal_id', 'container_number'], 'unique_active_container');

            // Foreign key constraints
            $table->foreign('terminal_id')->references('id')->on('terminals');
            $table->foreign('container_number')->references('container_number')->on('containers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_inventory');
    }
};
