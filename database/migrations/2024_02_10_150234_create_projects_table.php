<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('owner_id');
            // $table->unsignedBigInteger('material_id');
            $table->string('description', 100);
            $table->timestamps();

            $table->foreign('owner_id')->references('id')->on('users');
            // $table->foreign('material_id')->references('id')->on('materials');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
?>