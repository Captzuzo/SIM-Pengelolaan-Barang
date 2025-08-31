<?php

namespace App\Filament\Resources\BarangResource\Pages;

use App\Filament\Resources\BarangResource;
use App\Models\Barang;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ViewStok extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = BarangResource::class;
    protected static string $relationship = 'stokBarangs';
    protected static string $view = 'filament.resources.barang-resource.pages.view-stok';

    public Barang $record;

    public function mount($record): void
    {
        // $this->record = Barang::findOrFail($record);
    }



    // protected function getHeaderActions(): array
    // {
    //     return [

    //     ];
    // }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->record->stokBarangs()->getQuery()
            )
            ->columns([
                // Tables\Columns\TextColumn::make('stok_sisa')
                //     ->label('Jumlah Stok'),

                // Tables\Columns\TextColumn::make('harga_beli')
                //     ->label('Harga')
                //     ->money('idr'),

                // Tables\Columns\TextColumn::make('tanggal_masuk')
                //     ->label('Tanggal Masuk')
                //     ->date('d-m-Y'),

                // Tables\Columns\TextColumn::make('created_at')
                //     ->label('Dibuat')
                //     ->dateTime('d-m-Y H:i'),
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
            ]);
    }
}
