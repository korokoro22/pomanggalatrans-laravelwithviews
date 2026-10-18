<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
            $table->decimal('qty_satuan', 15, 2)->change();
            $table->decimal('stok_saat_ini', 15, 2)->change();
        });

        Schema::table('barang_masuk_detail', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
            $table->decimal('qty_satuan', 15, 2)->change();
        });

        Schema::table('paket_service_item', function (Blueprint $table) {
            $table->decimal('qty', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->integer('qty')->change();
            $table->integer('qty_satuan')->change();
            $table->integer('stok_saat_ini')->change();
        });

        Schema::table('barang_masuk_detail', function (Blueprint $table) {
            $table->integer('qty')->change();
            $table->integer('qty_satuan')->change();
        });

        Schema::table('paket_service_item', function (Blueprint $table) {
            $table->integer('qty')->change();
        });
    }
};