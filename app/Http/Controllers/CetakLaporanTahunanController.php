<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use PDF;

class CetakLaporanTahunanController extends Controller
{
    public function cetak(Request $request)
    {
        $tahun = $request->tahun;

        $penjualans = Penjualan::with('details.barang')
            ->whereYear('tanggal', $tahun)
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

        $pdf = PDF::loadView('pdf.laporan-tahunan', [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'tahun' => $tahun,
        ]);

        return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
    }
}