<?php

namespace App\Exports;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanHarianExport implements FromCollection, WithHeadings
{
    protected $mulai, $selesai;
     public ?string $tanggal = null;

    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;

    public function __construct($tanggalMulai, $tanggalSelesai)
    {
        $this->tanggalMulai   = Carbon::parse($tanggalMulai)->startOfDay();
        $this->tanggalSelesai = Carbon::parse($tanggalSelesai)->endOfDay();
    }

    public function collection()
    {
        return Penjualan::with('detail.barang')
            ->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai])
            ->get()
            ->map(function ($item) {
                return [
                    'No Invoice'   => $item->no_invoice,
                    'Tanggal'      => Carbon::parse($item->tanggal)->format('d-m-Y'),
                    'Total'        => $item->total,
                    'Bayar'        => $item->bayar,
                    'Sisa'         => $item->sisa,
                    'Laba'         => $item->laba ?? ($item->total - $item->detail->sum(fn($d) => $d->barang->harga_beli * $d->qty)),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Tanggal',
            'Total',
            'Bayar',
            'Sisa',
            'Laba',
        ];
    }
}
