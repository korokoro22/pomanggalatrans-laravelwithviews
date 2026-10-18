<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * REVISI:SEMUA BUS
     * Ubah bus_id di paket_service menjadi nullable (NULL = Semua Bus)
     */
    public function up(): void
    {
        Schema::table('paket_service', function (Blueprint $table) {
            $table->foreignId('bus_id')->nullable()->change();
        });

        // Ubah bus_id = 0 menjadi NULL (jika ada data existing)
        \Illuminate\Support\Facades\DB::table('paket_service')
            ->where('bus_id', 0)
            ->update(['bus_id' => null]);
    }

    public function down(): void
    {
        Schema::table('paket_service', function (Blueprint $table) {
            $table->foreignId('bus_id')->nullable(false)->change();
        });
    }
};
