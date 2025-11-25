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
        Schema::create('containers', function (Blueprint $table) {
            $table->string('container_number', 11)->primary();
            $table->enum('size', ['20', '40', '45'])->nullable(false);
            $table->string('type', 50)->nullable(false);
            $table->enum('ownership', ['COC', 'SOC', 'FU'])->nullable(false);
            $table->string('iso_code', 4)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
