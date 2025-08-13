<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function cetak($id)
    {
        $penjualan = Penjualan::with('detail.barang', 'pelanggan')->findOrFail($id);
        $pdf = Pdf::loadView('pdf.invoice-penjualan', compact('penjualan'));
        return $pdf->download("Invoice-$penjualan->no_invoice.pdf");
    }
}