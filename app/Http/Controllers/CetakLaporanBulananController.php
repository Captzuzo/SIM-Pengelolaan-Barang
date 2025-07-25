<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use PDF;

class CetakLaporanBulananController extends Controller
{
    public function cetak(Request $request)
    {
        $bulan = $request->bulan;
        $bulanNum = date('m', strtotime($bulan));
        $tahun = date('Y', strtotime($bulan));

        $penjualans = Penjualan::with('details.barang')
            ->whereMonth('tanggal', $bulanNum)
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

        $pdf = PDF::loadView('pdf.laporan-bulanan', [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'bulan' => $bulan,
        ]);

        return $pdf->download('laporan-bulanan-' . date('Y-m', strtotime($bulan)) . '.pdf');
    }
}