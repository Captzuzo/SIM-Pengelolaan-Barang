<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\StokBarang;

class StokService
{
    public static function keluarkanStokFIFO($barangId, $jumlahKeluar)
    {
        $batchList = StokBarang::where('barang_id', $barangId)
            ->where('stok_sisa', '>', 0)
            ->orderBy('tanggal_masuk', 'asc')
            ->get();

        foreach ($batchList as $batch) {
            if ($jumlahKeluar <= 0) break;

            $ambil = min($batch->stok_sisa, $jumlahKeluar);

            $batch->stok_sisa -= $ambil;
            $batch->stok_keluar += $ambil;
            $batch->save();

            $jumlahKeluar -= $ambil;
        }

        // Update total stok barang
        $barang = Barang::find($barangId);
        $barang->stok = StokBarang::where('barang_id', $barangId)->sum('stok_sisa');
        $barang->save();
    }
}