<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StokResource\Pages;
use App\Filament\Resources\StokResource\RelationManagers;
use App\Models\StokBarang;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StokResource extends Resource
{
    protected static ?string $model = StokBarang::class;
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationLabel = 'Stok Barang';
    protected static ?string $slug = 'stok';
    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama_barang') // ✅ langsung ambil dari relasi
                    ->label('Nama Barang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('stok_sisa') // ✅ pakai stok_sisa biar akurat
                    ->label('Jumlah Stok')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_satuan') // ✅ sesuaikan field di stok_barangs
                    ->label('Harga Beli')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_masuk') // ✅ field dari stok_barangs
                    ->label('Tanggal Masuk')
                    ->date('d-m-Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d-m-Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoks::route('/'),
            // 'create' => Pages\CreateStok::route('/create'),
            // 'edit' => Pages\EditStok::route('/{record}/edit'),
        ];
    }
}