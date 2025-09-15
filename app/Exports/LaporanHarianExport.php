<?php

namespace App\Exports;

use App\Models\Penjualan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class LaporanHarianExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithStyles,
    WithTitle,
    WithColumnFormatting
{
    protected $tanggalMulai, $tanggalSelesai;
    protected string $currencyCode;

    public function __construct($tanggalMulai, $tanggalSelesai)
    {
        $this->tanggalMulai   = Carbon::parse($tanggalMulai)->startOfDay();
        $this->tanggalSelesai = Carbon::parse($tanggalSelesai)->endOfDay();
        $this->currencyCode   = $mataUang ?? env('APP_CURRENCY', 'IDR');
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
                    'Total Penjualan' => $item->total,
                    'Bayar'        => $item->bayar,
                    'Sisa (Piutang)' => $item->sisa,
                    'Laba Bersih'  => $item->laba ?? ($item->total - $item->detail->sum(fn($d) => $d->barang->harga_beli * $d->qty)),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Tanggal',
            'Total Penjualan',
            'Bayar',
            'Sisa (Piutang)',
            'Laba Bersih',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Header style
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'color' => ['rgb' => '228B22'], // hijau Excel
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],
        ]);

        // Set border for all data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle("A1:F{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function columnFormats(): array
    {
        $symbol = $this->getCurrencySymbol();
        return [
            'C' => $symbol . NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Penjualan
            'D' => $symbol . NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Bayar
            'E' => $symbol . NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Sisa
            'F' => $symbol . NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Laba
        ];
    }

    /**
     * Ambil simbol mata uang berdasarkan kode
     */
    protected function getCurrencySymbol(): string
    {
        return match ($this->currencyCode) {
            'USD' => '$',
            'EUR' => '€',
            'JPY' => '¥',
            'GBP' => '£',
            'AUD' => 'A$',
            'CAD' => 'C$',
            'CHF' => 'CHF',
            'CNY' => '¥',
            'SGD' => 'S$',
            'HKD' => 'HK$',
            'NZD' => 'NZ$',
            'KRW' => '₩',
            'INR' => '₹',
            'THB' => '฿',
            'MYR' => 'RM',
            'PHP' => '₱',
            'VND' => '₫',
            'AED' => 'د.إ',
            'SAR' => '﷼',
            default => 'Rp', // fallback Indonesia
        };
    }

    // public function columnFormats(): array
    // {
    //     $symbol = $this->getCurrencySymbol();

    //     return [
    //         'C' => '"' . $symbol . ' "#,##0',
    //         'D' => '"' . $symbol . ' "#,##0',
    //         'E' => '"' . $symbol . ' "#,##0',
    //         'F' => '"' . $symbol . ' "#,##0',
    //     ];
    // }

}
