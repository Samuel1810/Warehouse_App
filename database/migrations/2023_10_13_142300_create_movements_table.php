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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('cabinet_id');
            $table->timestamp('data_movimento')->useCurrent();
            $table->tinyInteger('tipo_movimento');
            $table->float('quantidade');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->foreign('cabinet_id')->references('id')->on('cabinets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
