<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->enum('kategori', ['normal', 'nota_jalan'])->default('normal')->after('bus_id');
            $table->string('no_invoice')->nullable()->after('kategori');
            $table->string('supplier')->nullable()->after('no_invoice');
            $table->string('bukti_nota')->nullable()->after('supplier');
        });
    }

    public function down()
    {
        Schema::table('transaksi_keluar', function (Blueprint $table) {
            $table->dropColumn(['kategori', 'no_invoice', 'supplier', 'bukti_nota']);
        });
    }
};