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
        Schema::create('report_student', function (Blueprint $table) {
            $table->primary(['report_id', 'student_id']);
            $table->unsignedBigInteger('report_id');
            $table->unsignedBigInteger('student_id');

            $table->foreign('report_id')->references('id')->on('reports')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
