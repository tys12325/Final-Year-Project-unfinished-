<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); 
            $table->integer('filters1');
            $table->integer('filters2');
            $table->integer('filters3');
            $table->integer('filters4');
            $table->integer('rating');
            $table->text('comment')->nullable();
            $table->timestamps();

            //Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('feedback');
    }
};

