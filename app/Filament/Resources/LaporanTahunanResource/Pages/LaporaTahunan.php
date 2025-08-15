<?php

namespace App\Filament\Pages\Laporan;

use App\Models\Barang;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanTahunanPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-date-range';
    protected static ?string $title = 'Laporan Tahunan';
    protected static ?string $slug = 'laporan-tahunan';
    protected static ?int $navigationSort = 23;

    protected static string $view = 'filament.pages.laporan.laporan-tahunan';

    public ?string $tahun = null;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (!$user) {
            return false;
        }

        if ($user->hasRole('Admin')) {
            return true;
        }

        if ($user->hasPermissionTo('View Laporan Tahunan')) {
            return true;
        }

        return false;
    }

    // protected function getFormSchema(): array
    // {
    //     return [
    //         Forms\Components\Select::make('tahun')
    //             ->label('Pilih Tahun')
    //             ->options($this->getTahunOptions())
    //             ->required()
    //             ->searchable()
    //     ];
    // }
    protected function getFormSchema(): array
    {
        $tahunSekarang = date('Y');
        $tahunMulai = $tahunSekarang - 5; // 5 tahun terakhir

        return [
            Select::make('tahun')
                ->label('Pilih Tahun')
                ->options(array_combine(range($tahunSekarang, $tahunMulai), range($tahunSekarang, $tahunMulai)))
                ->required(),
        ];
    }

    protected function getTahunOptions(): array
    {
        $years = Penjualan::selectRaw('YEAR(tanggal) as year')
            ->groupBy('year')
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        $options = [];
        foreach ($years as $year) {
            $options[$year] = $year;
        }

        return $options;
    }

    public function generate()
    {
        // $this->validateOnly('tahun');
        $this->validate([
            'tahun' => 'required|digits:4',
        ]);

        $penjualans = Penjualan::with('detail.barang')
            ->whereYear('tanggal', $this->tahun)
            ->orderBy('tanggal', 'asc')
            ->get();

        $barang = Barang::all();
        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;
        $totalLaba = 0;

        // foreach ($penjualans as $penjualan) {
        //     foreach ($penjualan->detail as $detail) {
        //         if ($detail->barang) {
        //             $totalModal += $detail->barang->harga_beli * $detail->qty;
        //         }
        //     }
        // }
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

        // $this->data = [
        //     'penjualans' => $penjualans,
        //     'total_penjualan' => $totalPenjualan,
        //     'total_modal' => $totalModal,
        //     'total_laba' => $totalPenjualan - $totalModal,
        // ];
        $this->data = [
            'penjualans' => $penjualans,
            'barang' => $barang,
            'total_penjualan' => $totalPenjualan,
            'total_modal' => $totalModal,
            'total_laba' => $totalLaba,
        ];
    }
}
