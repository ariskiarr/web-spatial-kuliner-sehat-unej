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
        Schema::create('menu_makan', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('tempat_id')->constrained('tempat_makan');
            $table->string('nama_menu');
            $table->decimal('harga');
            $table->decimal('kalori')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_makan');
    }
};
