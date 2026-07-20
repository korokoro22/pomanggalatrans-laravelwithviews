<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('nota_jalan_detail');
        Schema::dropIfExists('nota_jalan');
    }

    public function down()
    {
        // Sengaja tidak di-recreate — struktur lama sudah tidak dipakai
    }
};