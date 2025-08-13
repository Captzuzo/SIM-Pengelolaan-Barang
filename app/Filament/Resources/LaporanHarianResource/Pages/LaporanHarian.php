<?php

namespace App\Filament\Pages\Laporan;;

use App\Filament\Resources\LaporanHarianResource;
// use Filament\Pages\Page;
// use Filament\Resources\Pages\Page;

use App\Models\Penjualan;
use Filament\Forms;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;

class LaporanHarianPage extends Page
{
    // protected static string $resource = Lapo::class;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Laporan Harian';
    protected static ?string $slug = 'laporan-harian';
    protected static ?int $navigationSort = 21;

    protected static string $view = 'filament.pages.laporan.laporan-harian';
    // protected static string $view = 'filament.pages.laporan.laporan-stok';

    public ?string $tanggal = null;

    public array $data = [];

    // public function mount(): void
    // {
    //     $this->form->fill();
    // }

    public function mount(): void
    {
        // if (! static::canAccess()) {
        //     abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk melihat Laporan Harian.');
        // }

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

        if ($user->hasPermissionTo('View Laporan Harian')) {
            return true;
        }

        return false;
    }



    protected function getFormSchema(): array
    {
        return [
            Forms\Components\DatePicker::make('tanggal')
                ->label('Pilih Tanggal')
                ->required(),
        ];
    }

    public function generate()
    {
        $this->validateOnly('tanggal');

        $penjualans = Penjualan::with('detail.barang')
            ->whereDate('tanggal', $this->tanggal)
            ->get();

        $totalPenjualan = $penjualans->sum('total');
        $totalModal = 0;

        foreach ($penjualans as $penjualan) {
            foreach ($penjualan->detail as $detail) {
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