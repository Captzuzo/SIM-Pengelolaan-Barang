<?php

namespace App\Filament\Pages\Laporan;

use App\Models\Penjualan;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanTahunanPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
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

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('tahun')
                ->label('Pilih Tahun')
                ->options($this->getTahunOptions())
                ->required()
                ->searchable()
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
        $this->validateOnly('tahun');

        $penjualans = Penjualan::with('details.barang')
            ->whereYear('tanggal', $this->tahun)
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