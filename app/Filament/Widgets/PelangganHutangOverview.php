<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class PelangganHutangOverview extends BaseWidget
{
    protected static ?string $label = 'Pelanggan Hutang';
    protected function getTableQuery(): Builder
    {
        return Penjualan::whereHas('pelanggan', function ($query) {
            $query->where('status_pembayaran', 'belum bayar');
        });
        return Pelanggan::whereHas('penjualans', function ($query) {
            $query->where('status_pembayaran', 'belum bayar');
            $query->where('alamat_lengkap');
        });
        // return Pelanggan::whereHas('penjualans', function ($query) {
        //     $query->where('status_pembayaran', 'belum bayar');
        // });
        return Barang::where('stok', '<=', 5)->orderBy('stok');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('No')
                ->label('No')
                ->getStateUsing(function ($record, $livewire, $column) {
                    return ($livewire->getTableRecords()->search($record) + 1);
                }),
            Tables\Columns\TextColumn::make('pelanggan.nama'),
            Tables\Columns\TextColumn::make('total'),
            Tables\Columns\TextColumn::make('sisa'),
            Tables\Columns\TextColumn::make('pelanggan.alamat_lengkap'),
        ];
    }
}
