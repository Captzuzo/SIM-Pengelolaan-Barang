<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use PDF;


class CetakLaporanLabaController extends Controller
{
    public function cetak(Request $request)
    {

        $tanggalMulai = $request->tanggalMulai;
        $tanggalSelesai = $request->tanggalSelesai;

        $penjualans = Penjualan::with('details.barang')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->get();

        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;

        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->details as $detail) {
                if ($detail->barang) {
                    $totalModal += $detail->barang->harga_beli * $detail->qty;
                }
            }
        }

        $totalLaba = $totalPenjualan - $totalModal;

        $pdf = PDF::loadView('pdf.laporan-laba', [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ]);

        return $pdf->download('laporan-laba.pdf');
    }
}