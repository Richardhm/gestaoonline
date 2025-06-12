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
        Schema::create('ars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acn2_id')->constrained('acn2s')->onDelete('cascade');
            $table->string('nome');
            $table->string('tipo')->nullable();
            $table->integer('situacao')->nullable();
            $table->boolean('open')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ars');
    }
};
