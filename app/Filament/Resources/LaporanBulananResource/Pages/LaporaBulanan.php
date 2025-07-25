<?php

namespace App\Filament\Pages\Laporan;

use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanBulananPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $title = 'Laporan Bulanan';
    protected static ?string $slug = 'laporan-bulanan';
    protected static ?int $navigationSort = 22;

    protected static string $view = 'filament.pages.laporan.laporan-bulanan';

    public ?string $bulan = null;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return $user && (
            $user->hasRole('Admin') ||
            $user->hasPermissionTo('View Laporan Bulanan')
        );
    }

    protected function getFormSchema(): array
    {
        return [
            DatePicker::make('bulan')
                ->label('Pilih Bulan')
                ->required()
                ->placeholder('Pilih Bulan dan Tahun')
                ->displayFormat('F Y') // Menampilkan nama bulan dan tahun (contoh: Juli 2025)
                ->format('Y-m') // Format yang dikirimkan (contoh: 2025-07)
                ->closeOnDateSelection()
                ->native(false), // Nonaktifkan native picker agar lebih fleksibel
        ];
    }

    public function generate()
    {
        $this->validateOnly('bulan');

        $bulan = date('m', strtotime($this->bulan));
        $tahun = date('Y', strtotime($this->bulan));

        $penjualans = Penjualan::with('details.barang')
            ->whereMonth('tanggal', $bulan)
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

        $this->data = [
            'penjualans' => $penjualans,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalPenjualan - $totalModal,
        ];
    }
}