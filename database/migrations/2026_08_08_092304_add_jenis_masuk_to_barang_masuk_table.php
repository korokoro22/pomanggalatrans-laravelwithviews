<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->enum('jenis_masuk', ['nota', 'stok_awal'])
                  ->default('nota')
                  ->after('id');

            $table->string('no_invoice', 255)->nullable()->change();
            $table->string('supplier', 255)->nullable()->change();
            $table->string('penerima', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('jenis_masuk');

            $table->string('no_invoice', 255)->nullable(false)->change();
            $table->string('supplier', 255)->nullable(false)->change();
            $table->string('penerima', 255)->nullable(false)->change();
        });
    }
};