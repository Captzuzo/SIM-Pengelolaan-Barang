<?php

namespace App\Filament\Resources\LaporanStokResource\Pages;

use App\Filament\Resources\LaporanStokResource;
use App\Models\Barang;
use Filament\Pages\Page;

class LaporanStokPage extends Page
{
    // protected static string $resource = LaporanStok::class;
    protected static bool $shouldRegisterNavigation = true;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Laporan Stok';
    protected static ?string $slug = 'laporan-stok';
    protected static ?int $navigationSort = 24;
    protected static string $view = 'filament.pages.laporan.laporan-stok';

    public $data = [];

    // public function mount()
    // {
    //     $this->loadStok();
    // }

    public function mount(): void
    {
        if (! static::canAccess()) {
            abort(403, 'Akses ditolak: Anda tidak memiliki izin untuk melihat Laporan Harian.');
        }

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

    public function loadStok()
    {
        $barangs = Barang::with('kategori')->get();

        $this->data['barangs'] = $barangs;
        $this->data['total_nilai_stok'] = $barangs->sum(function ($barang) {
            return $barang->stok * $barang->harga_beli;
        });
    }
}
