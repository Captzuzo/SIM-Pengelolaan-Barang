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
        Schema::create('mutasi_stoks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('barang_id');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->integer('qty');
            $table->decimal('harga_beli')->nullable();
            $table->uuid('barang_beli_id')->nullable();
            $table->uuid('penjualan_id')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')
                ->references('id')
                ->on('barangs')
                ->onDelete('cascade');

                $table->foreign('barang_beli_id')
                ->references('id')
                ->on('barang_belis')
                ->onDelete('set null');

            // relasi opsional ke penjualans
            $table->foreign('penjualan_id')
                ->references('id')
                ->on('penjualans')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mutasi_stok');
    }
};
