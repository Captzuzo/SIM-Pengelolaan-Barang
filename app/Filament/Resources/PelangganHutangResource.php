<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganHutangResource\Pages;
use App\Filament\Resources\PelangganHutangResource\RelationManagers;
use App\Models\PelangganHutang;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class PelangganHutangResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $slug = 'pelanggan-hutang';
    protected static ?string $pluralLabel = 'Pelanggan Hutang';
    protected static ?string $navigationLabel = 'Pelanggan Hutang';
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Card::make()
                //     ->schema([])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no_invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('pelanggan.nama')
                    ->label('Pelanggan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total')
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('bayar')
                    ->money('IDR', true),

                Tables\Columns\TextColumn::make('sisa')
                    ->money('IDR', true),

                BadgeColumn::make('status_pembayaran')
                    ->colors([
                        'success' => 'lunas',
                        'danger' => 'belum bayar',
                    ])
                    ->formatStateUsing(fn($state) => Str::title($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i'),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('status_pembayaran', 'Belum Bayar');
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListPelangganHutangs::route('/'),
            'create' => Pages\CreatePelangganHutang::route('/create'),
            'edit' => Pages\EditPelangganHutang::route('/{record}/edit'),
        ];
    }
}
