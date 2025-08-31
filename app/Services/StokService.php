<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\MutasiStokModel;
use App\Models\StokBarang;

class StokService
{
    public static function keluarkanStokFIFO($barangId, $jumlahKeluar, $penjualanId)
    {
        $batchList = StokBarang::where('barang_id', $barangId)
            ->where('stok_sisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

       foreach ($batchList as $batch) {
            if ($jumlahKeluar <= 0) break;

            $ambil = min($batch->stok_sisa, $jumlahKeluar);

            // update stok batch
            $batch->stok_sisa -= $ambil;
            $batch->stok_keluar += $ambil;
            $batch->save();

            // catat ke mutasi stok
            MutasiStokModel::create([
                'barang_id'   => $barangId,
                'tipe'        => 'keluar',
                'qty'         => $ambil,
                'harga_beli'  => $batch->harga_beli, // ambil dari batch pembelian
                'barang_beli_id' => $batch->barang_beli_id ?? null,
                'penjualan_id'   => $penjualanId,
                'keterangan'  => $keterangan ?? 'Penjualan',
            ]);

            $jumlahKeluar -= $ambil;
        }

        // Update total stok barang
        $barang = Barang::find($barangId);
        $barang->stok = StokBarang::where('barang_id', $barangId)->sum('stok_sisa');
        $barang->save();
    }
}
