<?php

namespace App\Filament\Widgets;

use App\Models\Kategori;
use App\Models\Barang;
use App\Models\DetailPenjualan;
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
            // Card::make('Barang Habis', Barang::where('stok', '<=', 0)->count()),
            Card::make('Total Pelanggan', Pelanggan::count()),
            Card::make('Total Penjualan', Penjualan::count()),
            Card::make('Total Nilai Stok', function () {
                return 'Rp ' . number_format(
                    Barang::all()->sum(fn($barang) => $barang->stok * $barang->harga_beli),
                    0,
                    ',',
                    '.'
                );
            })
                ->description('Total nilai dari seluruh stok barang')
                ->color('success')
                ->icon('heroicon-o-banknotes')
                ->extraAttributes(['class' => 'text-lg font-bold']),
            Card::make('Barang Terlaris', function () {
                $terlaris = DetailPenjualan::with('barang')
                    ->selectRaw('barang_id, SUM(qty) as total_terjual')
                    ->groupBy('barang_id')
                    ->orderByDesc('total_terjual')
                    ->first();

                return $terlaris
                    ? "{$terlaris->barang->nama_barang} ({$terlaris->total_terjual} terjual)"
                    : 'Belum ada data';
            })
                // ->description('Barang dengan penjualan terbanyak')
                ->icon('heroicon-o-fire')
                ->color('warning'),
            Card::make('Total Uang Masuk', 'Rp ' . number_format(Penjualan::sum('bayar'), 0, ',', '.')),
            Card::make('Total Piutang', 'Rp ' . number_format(Penjualan::sum('sisa'), 0, ',', '.')),
        ];
    }
}
