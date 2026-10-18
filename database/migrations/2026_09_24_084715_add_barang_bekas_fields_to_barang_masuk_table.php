<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Ubah enum jenis_masuk supaya menampung nilai baru
        DB::statement("ALTER TABLE barang_masuk MODIFY COLUMN jenis_masuk ENUM('nota', 'stok_awal', 'barang_bekas') NOT NULL DEFAULT 'nota'");

        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->foreignId('bus_id')->nullable()->after('jenis_masuk')->constrained('bus')->nullOnDelete();
            $table->text('keterangan')->nullable()->after('bus_id');
        });
    }

    public function down()
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropConstrainedForeignId('bus_id');
            $table->dropColumn('keterangan');
        });

        DB::statement("ALTER TABLE barang_masuk MODIFY COLUMN jenis_masuk ENUM('nota', 'stok_awal') NOT NULL DEFAULT 'nota'");
    }
};