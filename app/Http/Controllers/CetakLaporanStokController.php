<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CetakLaporanStokController extends Controller
{
    public function cetak()
    {
        $barangs = Barang::with('kategori')->get();

        $total_nilai_stok = $barangs->sum(function ($barang) {
            return $barang->stok * $barang->harga_beli;
        });

        $pdf = Pdf::loadView('pdf.laporan-stok', [
            'barangs' => $barangs,
            'total_nilai_stok' => $total_nilai_stok,
        ]);

        return $pdf->download('laporan-stok.pdf');
    }
}