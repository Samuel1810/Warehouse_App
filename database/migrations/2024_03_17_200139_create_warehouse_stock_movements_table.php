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
        Schema::create('warehouse_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('cabinet_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('project_id');
            $table->float('quantity');
            $table->integer('top');
            $table->integer('left');

            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('cabinet_id')->references('id')->on('cabinets');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('project_id')->references('id')->on('projects');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_stock_movements');
    }
};
