<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('transaksi_keluar_detail', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi_keluar_detail', 'keterangan')) {
                $table->string('keterangan')->nullable()->after('nama_item');
            }
        });

        DB::statement("ALTER TABLE transaksi_keluar_detail MODIFY COLUMN tipe ENUM('per_item', 'paket_service', 'nota_jalan', 'biaya_pengerjaan')");
    }

    public function down()
    {
        Schema::table('transaksi_keluar_detail', function (Blueprint $table) {
            if (Schema::hasColumn('transaksi_keluar_detail', 'keterangan')) {
                $table->dropColumn('keterangan');
            }
        });

        DB::statement("ALTER TABLE transaksi_keluar_detail MODIFY COLUMN tipe ENUM('per_item', 'paket_service', 'nota_jalan')");
    }
};
