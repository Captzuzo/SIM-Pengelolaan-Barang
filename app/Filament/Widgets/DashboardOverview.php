<?php

namespace App\Filament\Widgets;

use App\Models\Kategori;
use App\Models\Barang;
use App\Models\DetailPenjualan;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use App\Models\Supplier;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class DashboardOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Supplier', view('components.stats-card', [
                'value' => Supplier::count(),
                'icon'  => 'heroicon-o-truck',
            ]))
                ->color('dark'),

            // Card::make('Total Barang', Barang::count())
            //     ->icon('heroicon-o-archive-box')
            //     ->color('primary'),

            Card::make('Total Barang', view('components.stats-card', [
                'value' => Barang::count(),
                'icon'  => 'heroicon-o-archive-box',
            ]))
                ->color('dark'),

            // Card::make('Total Pelanggan', Pelanggan::count())
            //     ->icon('heroicon-o-user-group')
            //     ->color('primary'),
            Card::make('Total Pelanggan', view('components.stats-card', [
                'value' => Pelanggan::count(),
                'icon'  => 'heroicon-o-user-group',
            ]))
                ->color('dark'),

            // Card::make('Total Penjualan', Penjualan::count())
            //     ->icon('heroicon-o-shopping-cart')
            //     ->color('primary'),
            Card::make('Total Penjualan', view('components.stats-card', [
                'value' => Penjualan::count(),
                'icon'  => 'heroicon-o-shopping-cart',
            ]))
                ->color('dark'),

            // Card::make('Total Nilai Stok', 'Rp ' . number_format(
            //     Barang::all()->sum(fn($barang) => $barang->stok * $barang->harga_beli),
            //     0,
            //     ',',
            //     '.'
            // ))
            //     ->description('Total nilai dari seluruh stok barang')
            //     ->icon('heroicon-o-banknotes')
            //     ->color('success'),

            Card::make(
                'Total Nilai Stok',
                view('components.stats-card', [
                    'value' => 'Rp ' . number_format(
                        Barang::all()->sum(fn($barang) => $barang->stok * $barang->harga_beli),
                        0,
                        ',',
                        '.'
                    ),
                    'icon' => 'heroicon-o-banknotes',
                ])
            )
                ->description('Total nilai dari seluruh stok barang')
                ->color('success'),


            // Card::make('Barang Terlaris', $this->getBarangTerlaris())
            //     ->icon('heroicon-o-fire')
            //     ->color('warning'),

            // Card::make('Total Uang Masuk', 'Rp ' . number_format(Penjualan::sum('total'), 0, ',', '.'))
            //     ->icon('heroicon-o-currency-dollar')
            //     ->color('success'),

            // Card::make('Total Piutang', 'Rp ' . number_format(Penjualan::sum('sisa'), 0, ',', '.'))
            //     ->icon('heroicon-o-credit-card')
            //     ->color('danger'),
            Card::make(
                'Barang Terlaris',
                view('components.stats-card', [
                    'value' => $this->getBarangTerlaris(),
                    'icon'  => 'heroicon-o-fire',
                ])
            )
                ->color('warning'),

            Card::make(
                'Total Uang Masuk',
                view('components.stats-card', [
                    'value' => 'Rp ' . number_format(Penjualan::sum('total'), 0, ',', '.'),
                    'icon'  => 'heroicon-o-currency-dollar',
                ])
            )
                ->color('success'),

            Card::make(
                'Total Piutang',
                view('components.stats-card', [
                    'value' => 'Rp ' . number_format(Penjualan::sum('sisa'), 0, ',', '.'),
                    'icon'  => 'heroicon-o-credit-card',
                ])
            )
                ->color('danger'),

        ];
    }

    private function getBarangTerlaris()
    {
        $terlaris = DetailPenjualan::with('barang')
            ->selectRaw('barang_id, SUM(qty) as total_terjual')
            ->groupBy('barang_id')
            ->orderByDesc('total_terjual')
            ->first();

        return $terlaris
            ? "{$terlaris->barang->nama_barang} ({$terlaris->total_terjual} terjual)"
            : 'Belum ada data';
    }
}
