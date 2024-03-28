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
        Schema::create('purchase_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('material_id');
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('manufacturer_id');
            $table->float('quantity');
            $table->date('date');
            $table->tinyInteger('movement_type')->default(0);
            $table->string('payment_proof')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('material_id')->references('id')->on('materials');
            $table->foreign('supplier_id')->references('id')->on('fornecedores');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_materials');
    }
};
