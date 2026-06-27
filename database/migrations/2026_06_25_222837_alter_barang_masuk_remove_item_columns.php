<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropForeign(['barang_id']);
            $table->dropColumn([
                'barang_id',
                'qty',
                'satuan',
                'qty_satuan',
                'nominal',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->foreignId('barang_id')->nullable()->constrained('barang')->nullOnDelete();
            $table->integer('qty')->nullable();
            $table->string('satuan')->nullable();
            $table->integer('qty_satuan')->nullable();
            $table->decimal('nominal', 15, 2)->default(0);
        });
    }
};