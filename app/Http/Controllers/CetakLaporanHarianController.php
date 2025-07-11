<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use PDF;

class CetakLaporanHarianController extends Controller
{
    public function cetak(Request $request)
    {
        $tanggal = $request->tanggal;

        $penjualans = Penjualan::with('details.barang')
            ->whereDate('tanggal', $tanggal)
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

        $pdf = PDF::loadView('pdf.laporan-harian', [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'tanggal' => $tanggal,
        ]);

        return $pdf->download('laporan-harian-' . $tanggal . '.pdf');
    }
}
