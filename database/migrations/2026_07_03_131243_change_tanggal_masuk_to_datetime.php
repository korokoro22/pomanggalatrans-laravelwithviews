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
    Schema::table('barang', function (Blueprint $table) {
        $table->dateTime('tanggal_masuk')->change();
    });

    Schema::table('barang_masuk', function (Blueprint $table) {
        $table->dateTime('tanggal_masuk')->change();
    });
}

public function down(): void
{
    Schema::table('barang', function (Blueprint $table) {
        $table->date('tanggal_masuk')->change();
    });

    Schema::table('barang_masuk', function (Blueprint $table) {
        $table->date('tanggal_masuk')->change();
    });
}
};
