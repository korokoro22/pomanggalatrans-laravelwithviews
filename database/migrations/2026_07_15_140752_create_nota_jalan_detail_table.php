<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nota_jalan_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_jalan_id')->constrained('nota_jalan')->onDelete('cascade');
            $table->string('nama_item');
            $table->unsignedInteger('qty');
            $table->string('satuan');
            $table->unsignedBigInteger('harga_satuan');
            $table->unsignedBigInteger('subtotal');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nota_jalan_detail');
    }
};