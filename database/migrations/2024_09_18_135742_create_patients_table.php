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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 255);
            $table->string('parent_name', 255)->nullable();
            $table->date('date_of_birth');
            $table->string('gender', 10);
            $table->string('phone', 20);
            $table->string('address', 255);
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('medical_history')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
