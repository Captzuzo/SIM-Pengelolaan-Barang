<?php

namespace App\Filament\Pages\Laporan;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Illuminate\Contracts\View\View;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;

class LaporanLabaPage extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    public ?string $tanggalMulai = null;
    public ?string $tanggalSelesai = null;
    public array $data = [
        'total_penjualan' => 0,
        'total_modal' => 0,
        'total_laba' => 0,
        'penjualans' => [],
    ];

    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Laporan Laba';
    protected static ?string $slug = 'laporan-laba';
    protected static ?int $navigationSort = 90;

    protected static string $view = 'filament.pages.laporan.laporan-laba';


    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('tanggalMulai')->label('Dari Tanggal')->required(),
            DatePicker::make('tanggalSelesai')->label('Sampai Tanggal')->required(),
        ];
    }

    public function generate(): void
    {
        $penjualans = Penjualan::with('details.barang')->whereBetween('tanggal', [$this->tanggalMulai, $this->tanggalSelesai])->get();

        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;
        $detailPenjualans = [];

        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->details as $detail) {
                if ($detail->barang) {
                    $totalModal += $detail->barang->harga_beli * $detail->qty;
                }

                // Tambahkan setiap detail ke array
                $detailPenjualans[] = (object)[
                    'no_invoice' => $penjualan->no_invoice,
                    'tanggal' => $penjualan->tanggal,
                    'barangs' => $detail->barang,
                    'total' => $detail->subtotal,
                ];
            }
        }

        $this->data = [
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalPenjualan - $totalModal,
            'penjualans' => $penjualans,
            'detail_penjualans' => $detailPenjualans, // ← tambahkan ini
        ];
    }
}