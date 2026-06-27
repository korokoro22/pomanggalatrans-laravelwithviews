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
        Schema::create('keuangan_armada', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('bus')->cascadeOnDelete();
            $table->tinyInteger('periode_bulan');
            $table->smallInteger('periode_tahun');
            $table->decimal('pemasukan', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_armada');
    }
};
