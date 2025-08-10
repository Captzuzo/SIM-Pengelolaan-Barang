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
        Schema::create('detail_penjualans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('penjualan_id');
            $table->foreign('penjualan_id')
                ->references('id')
                ->on('penjualans')
                ->onDelete('cascade');
            $table->foreignUuid('barang_id')
                ->constrained('barangs')
                ->onDelete('cascade');
            $table->integer('qty');
            $table->integer('harga_satuan');
            $table->integer('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_penjualans');
    }
};
