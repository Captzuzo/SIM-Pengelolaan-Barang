<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use PDF;

class CetakLaporanTahunanController extends Controller
{
    // public function cetak(Request $request)
    // {
    //     $tahun = $request->tahun;

    //     $penjualans = Penjualan::with('detail.barang')
    //         ->whereYear('tanggal', $tahun)
    //         ->get();

    //     $totalPenjualan = $penjualans->sum('total');
    //     $totalModal = 0;

    //     foreach ($penjualans as $penjualan) {
    //         foreach ($penjualan->details as $detail) {
    //             if ($detail->barang) {
    //                 $totalModal += $detail->barang->harga_beli * $detail->qty;
    //             }
    //         }
    //     }

    //     $totalLaba = $totalPenjualan - $totalModal;

    //     $pdf = PDF::loadView('pdf.laporan-tahunan', [
    //         'penjualans' => $penjualans,
    //         'total_penjualan' => $totalPenjualan,
    //         'total_modal' => $totalModal,
    //         'total_laba' => $totalLaba,
    //         'tahun' => $tahun,
    //     ]);

    //     return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
    // }
    public function cetak($tahun)
    {
        $penjualans = Penjualan::with('detail.barang')
            ->whereYear('tanggal', $tahun)
            ->get();

        // $total_penjualan = $penjualans->sum('total');
        // $total_modal = $penjualans->sum('modal');
        // $total_laba = $penjualans->sum('laba');
        // $totalLaba = 0;
        // $totalPiutang = 0;
        // $totalQty = 0;
        // $totalBarangBeli = 0;
        // $totalBarangJual = 0;

        // $data = [
        //     'penjualans' => $penjualans,
        //     'total_penjualan' => $total_penjualan,
        //     'total_modal' => $total_modal,
        //     'total_laba' => $total_laba,
        //     'tahun' => $tahun,
        //     'totalBarangJual' => $totalBarangJual,
        //     'totalLaba' => $totalLaba
        // ];

        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;
        $totalModal = 0;
        $totalLaba = 0;
        $totalPiutang = 0;
        $totalQty = 0;
        $totalBarangBeli = 0;
        $totalBarangJual = 0;

        foreach ($penjualans as $penjualan) {
            $penjualan->modal = 0; // modal per transaksi

            foreach ($penjualan->detail as $detail) {
                if ($detail->barang) {
                    $penjualan->modal += $detail->barang->harga_beli * $detail->qty;

                    // Hitung total qty dan harga di sini
                    $totalQty        += $detail->qty;
                    $totalBarangBeli += $detail->barang->harga_beli;
                    $totalBarangJual += $detail->barang->harga_jual;
                }
            }

            $penjualan->laba = $penjualan->total - $penjualan->modal;

            $totalModal   += $penjualan->modal;
            $totalLaba    += $penjualan->laba;
            $totalPiutang += $penjualan->sisa;
        }

        $data = [
            'penjualans' => $penjualans,
            'totalPiutang' => $totalPiutang,
            'totalQty' => $totalQty,
            'totalBarangBeli' => $totalBarangBeli,
            'totalBarangJual' => $totalBarangJual,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'tahun' => $tahun,
            'total_laba' => $totalLaba,
        ];

        $pdf = Pdf::loadView('pdf.laporan-tahunan', $data);
        return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
    }
}
