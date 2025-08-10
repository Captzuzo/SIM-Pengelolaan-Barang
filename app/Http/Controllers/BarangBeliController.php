<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangBeli;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangBeliController extends Controller
{
    public function cetak($id)
    {
        $pembelian = BarangBeli::with(['supplier', 'kasir', 'detailBarangBeli.barang'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('pdf.barang-beli', compact('pembelian'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Invoice-{$pembelian->no_invoice}.pdf");
    }

    // public function cetak($id)
    // {
    //     $barangBeli = BarangBeli::with('detailBarangBeli.barang', 'supplier')->findOrFail($id);
    //     $pdf = Pdf::loadView('pdf.invoice-barangBeli', compact('barangBeli'));
    //     return $pdf->download("Invoice-$barangBeli->no_invoice.pdf");
    // }
}
