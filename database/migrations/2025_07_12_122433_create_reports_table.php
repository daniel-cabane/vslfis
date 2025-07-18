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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reporter_id');
            $table->string('category', 255);
            $table->string('location')->nullable();
            $table->text('comment')->nullable();
            $table->boolean('finalized')->default(1);
            $table->unsignedBigInteger('filed_by')->nullable();
            $table->timestamps();
 
            $table->foreign('reporter_id')->references('id')->on('users');
            $table->foreign('filed_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
