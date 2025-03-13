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
        Schema::create('courses', function (Blueprint $table) {
            $table->id('courseID'); // Auto-increment primary key
            $table->string('courseName');
            $table->string('duration');
            $table->string('feesLocal');
            $table->string('feesInternational');
            $table->string('studyType');
            $table->string('studyLevel');
            $table->unsignedBigInteger('uniID');
            $table->foreign('uniID')->references('uniID')->on('uni')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
