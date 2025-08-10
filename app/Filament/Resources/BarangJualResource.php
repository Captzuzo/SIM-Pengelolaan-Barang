<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangJualResource\Pages;
use App\Filament\Resources\BarangJualResource\RelationManagers;
use App\Models\Barang;
use App\Models\BarangJual;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangJualResource extends Resource
{
    protected static ?string $model = BarangJual::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationLabel = 'Barang Jual';
    protected static ?int $navigationSort = 4;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('barang_id')
                            ->label('Barang')
                            ->options(function ($get, $record) {
                                // Barang_id yang sedang diedit (kalau mode edit)
                                $currentBarangId = $record?->barang_id;

                                // Ambil semua barang yang belum ada di tabel barang_jual
                                $query = \App\Models\Barang::query()
                                    ->whereNotIn('id', function ($q) use ($currentBarangId) {
                                        $q->select('barang_id')->from('barang_juals');
                                        if ($currentBarangId) {
                                            // Kecualikan barang yang sedang diedit
                                            $q->where('barang_id', '!=', $currentBarangId);
                                        }
                                    });

                                // Kalau mode edit, tambahkan barang yang sedang dipakai
                                if ($currentBarangId) {
                                    $query->orWhere('id', $currentBarangId);
                                }

                                return $query->pluck('nama_barang', 'id');
                            })
                            ->required(),

                        TextInput::make('harga_jual')
                            ->label('Harga Jual')
                            ->required()
                            ->prefix(fn(callable $get) => match ($get('mata_uang')) {
                                'USD' => '$',
                                'EUR' => '€',
                                'JPY' => '¥',
                                'GBP' => '£',
                                'AUD' => 'A$',
                                'CAD' => 'C$',
                                'CHF' => 'CHF',
                                'CNY' => '¥',
                                'SGD' => 'S$',
                                'HKD' => 'HK$',
                                'NZD' => 'NZ$',
                                'KRW' => '₩',
                                'INR' => '₹',
                                'THB' => '฿',
                                'MYR' => 'RM',
                                'PHP' => '₱',
                                'VND' => '₫',
                                'AED' => 'د.إ',
                                'SAR' => '﷼',
                                default => 'Rp',
                            })
                            ->numeric()
                            ->dehydrateStateUsing(fn($state) => str_replace('.', '', $state))
                            ->afterStateHydrated(fn($state, callable $set) => $set('harga_jual', number_format($state, 0, ',', '.'))),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('barang.nama_barang')->label('Barang')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('harga_jual')->money('idr', true),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
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
            'index' => Pages\ListBarangJuals::route('/'),
            'create' => Pages\CreateBarangJual::route('/create'),
            'edit' => Pages\EditBarangJual::route('/{record}/edit'),
        ];
    }
}
