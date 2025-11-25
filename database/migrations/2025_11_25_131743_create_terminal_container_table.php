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
        Schema::create('terminal_container', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terminal_id');
            $table->string('container_number', 11);
            $table->timestamps();

            $table->foreign('terminal_id')->references('id')->on('terminals')->onDelete('cascade');
            $table->foreign('container_number')->references('container_number')->on('containers')->onDelete('cascade');

            $table->unique(['terminal_id', 'container_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terminal_container');
    }
};
