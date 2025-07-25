<?php

namespace App\Filament\Widgets;

use App\Models\Barang;
use App\Models\Pelanggan;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;

class PelangganHutangOverview extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Pelanggan::whereHas('penjualans', function ($query) {
            $query->where('status_pembayaran', 'belum bayar');
        });
        return Barang::where('stok', '<=', 5)->orderBy('stok');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('nama'),
            Tables\Columns\TextColumn::make('alamat_lengkap'),
        ];
    }
}