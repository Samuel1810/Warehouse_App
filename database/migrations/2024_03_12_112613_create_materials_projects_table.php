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
        Schema::create('materials_projects', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('project_id');

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
        Schema::dropIfExists('materials_projects');
    }
};
