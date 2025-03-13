<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('uni', function (Blueprint $table) {
            $table->id('uniID'); // Primary Key
            $table->string('uniName', 100);
            $table->string('Address', 200);
            $table->string('ContactNumber', 20);
            $table->string('OperationHour', 50);
            $table->string('DateOfOpenSchool', 50)->nullable();
            $table->string('Category', 50);
            $table->string('Description', 1000);
            $table->string('Founder', 50);
            $table->string('EstablishDate', 50);
            $table->integer('Ranking')->nullable();
            $table->integer('NumOfCourses')->nullable();
            $table->string('image')->nullable(); // ✅ New column for image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('uni');
    }
};

