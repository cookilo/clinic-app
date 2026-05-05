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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->string('unit', 50);
            $table->integer('purchase_price');
            $table->integer('sale_price');
            $table->integer('stock');
            $table->string('manufacturer', 255)->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('dosage_instructions')->nullable();
            $table->text('side_effects')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
