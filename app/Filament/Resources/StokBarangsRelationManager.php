<?php

namespace App\Filament\Resources\BarangResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;

class StokBarangsRelationManager extends RelationManager
{
    protected static string $relationship = 'stokBarangs';
    protected static ?string $title = 'Stok Barang';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama_barang')
                    ->label('Nama Barang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('stok_sisa')
                    ->label('Jumlah Stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan')
                    ->label('Harga Beli')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->date('d-m-Y'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i'),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }
}
