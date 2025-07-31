<?php

namespace App\Filament\Widgets;

use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class DashboardOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Kategori', Kategori::count()),
            Card::make('Total Barang', Barang::count()),
            Card::make('Barang Habis', Barang::where('stok', '<=', 0)->count()),
            Card::make('Total Pelanggan', Pelanggan::count()),
            Card::make('Total Penjualan', Penjualan::count()),
            Card::make('Total Uang Masuk', 'Rp ' . number_format(Penjualan::sum('bayar'), 0, ',', '.')),
            Card::make('Total Piutang', 'Rp ' . number_format(Penjualan::sum('sisa'), 0, ',', '.')),
        ];
    }
}
