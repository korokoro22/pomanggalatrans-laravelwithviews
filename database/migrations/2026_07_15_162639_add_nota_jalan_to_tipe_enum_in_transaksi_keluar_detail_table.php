<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE transaksi_keluar_detail MODIFY COLUMN tipe ENUM('per_item', 'paket_service', 'nota_jalan') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE transaksi_keluar_detail MODIFY COLUMN tipe ENUM('per_item', 'paket_service') NOT NULL");
    }
};