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
        Schema::create('barangs', function (Blueprint $table) {
            // $table->id();
            // $table->string('kode_barang');
            // $table->integer('kategori_id');
            // $table->string('nama_barang');
            // $table->integer('harga_beli');
            // $table->integer('harga_jual');
            // $table->integer('stok');
            // $table->string('satuan');
            // $table->timestamps();

            $table->uuid('id')->primary();
            $table->string('kode_barang')->unique();
            $table->unsignedBigInteger('kategori_id');
            $table->string('nama_barang');
            $table->integer('harga_beli');
            $table->integer('harga_jual');
            $table->string('satuan');
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategoris')->onDelete('cascade');

            // $table->foreign('barang_beli_id')->references('id')->on('barang_belis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
