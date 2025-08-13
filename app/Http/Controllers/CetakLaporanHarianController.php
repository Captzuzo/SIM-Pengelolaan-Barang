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
        $tanggalMulai = $request->query('tanggalMulai');
        $tanggalSelesai = $request->query('tanggalSelesai');

        $penjualans = Penjualan::with('detail.barang')
            ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
            ->orderBy('tanggal', 'asc')
            ->get();

        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;
        $totalLaba = 0;

        foreach ($penjualans as $penjualan) {
            $penjualan->modal = 0;
            foreach ($penjualan->detail as $detail) {
                if ($detail->barang) {
                    $penjualan->modal += $detail->barang->harga_beli * $detail->qty;
                }
            }
            $penjualan->laba = $penjualan->total - $penjualan->modal;
            $totalModal += $penjualan->modal;
            $totalLaba += $penjualan->laba;
        }

        $data = [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ];

        $pdf = Pdf::loadView('pdf.laporan-harian', $data);
        return $pdf->download('laporan-harian-' . $tanggalMulai . '-sampai-' . $tanggalSelesai . '.pdf');
    }
}