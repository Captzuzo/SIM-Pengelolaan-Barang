<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_barang_belis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('barang_beli_id');
            $table->uuid('barang_id');
            $table->integer('stok');
            $table->decimal('harga_satuan', 15, 2);
            $table->timestamps();

            $table->foreign('barang_beli_id')->references('id')->on('barang_belis')->onDelete('cascade');
            $table->foreign('barang_id')->references('id')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail__barang_belis');
    }
};
