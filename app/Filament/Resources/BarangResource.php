<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
// use Filament\Forms\Components\TextInput\Mask\NumericMask;
use Filament\Forms\Components\Mask;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
// use Filament\Support\Components\TextInput\Mask\NumericMask;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $slug = 'barang';
    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('kode_barang')
                            ->required()
                            ->maxLength(255)
                            ->readOnly(),

                        Select::make('kategori_id')
                            ->label('Kategori')
                            ->options(Kategori::all()->pluck('nama_kategori', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $kategori = Kategori::find($state);

                                if (!$kategori) return;

                                $kodeKategori = $kategori->kode_kategori; // Asumsi kamu punya kolom kode_kategori

                                // Hitung jumlah barang yang sudah ada di kategori itu
                                $count = Barang::where('kategori_id', $kategori->id)->count() + 1;

                                // Format nomor ke tiga digit
                                $nomor = str_pad($count, 3, '0', STR_PAD_LEFT);

                                // Format kode_barang
                                $kodeBarang = "BRG-{$kodeKategori}-{$nomor}";

                                $set('kode_barang', $kodeBarang);
                            }),

                        Forms\Components\TextInput::make('nama_barang')
                            ->required()
                            ->maxLength(255),

                        // Pilihan mata uang
                        Select::make('mata_uang')
                            ->label('Mata Uang')
                            ->options([
                                'IDR' => 'Rupiah (Rp)',
                                'USD' => 'Dollar Amerika ($)',
                                'EUR' => 'Euro (€)',
                                'JPY' => 'Yen Jepang (¥)',
                                'GBP' => 'Pound Inggris (£)',
                                'AUD' => 'Dollar Australia (A$)',
                                'CAD' => 'Dollar Kanada (C$)',
                                'CHF' => 'Franc Swiss (CHF)',
                                'CNY' => 'Yuan Tiongkok (¥)',
                                'SGD' => 'Dollar Singapura (S$)',
                                'HKD' => 'Dollar Hong Kong (HK$)',
                                'NZD' => 'Dollar Selandia Baru (NZ$)',
                                'KRW' => 'Won Korea (₩)',
                                'INR' => 'Rupee India (₹)',
                                'THB' => 'Baht Thailand (฿)',
                                'MYR' => 'Ringgit Malaysia (RM)',
                                'PHP' => 'Peso Filipina (₱)',
                                'VND' => 'Dong Vietnam (₫)',
                                'AED' => 'Dirham UEA (د.إ)',
                                'SAR' => 'Riyal Saudi (﷼)',
                            ])
                            ->required()
                            ->default('IDR')
                            ->live(), // agar perubahan langsung diterapkan

                        // Harga Beli
                        TextInput::make('harga_beli')
                            ->label('Harga Beli')
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
                            ->afterStateHydrated(fn($state, callable $set) => $set('harga_beli', number_format($state, 0, ',', '.'))),

                        // Harga Jual
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

                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->maxLength(255),

                        Select::make('satuan')
                            ->label('Satuan')
                            ->required()
                            ->options([
                                'pcs' => 'Pcs',
                                'unit' => 'Unit',
                                'kg' => 'Kilogram',
                                'g' => 'Gram',
                                'ltr' => 'Liter',
                                'm' => 'Meter',
                                'cm' => 'Centimeter',
                                'dus' => 'Dus',
                                'pak' => 'Pak',
                                'roll' => 'Roll',
                                'box' => 'Box',
                                'botol' => 'Botol',
                                'kaleng' => 'Kaleng',
                                'sak' => 'Sak',
                                'set' => 'Set',
                            ])
                            ->searchable() // Agar mudah dicari jika opsinya banyak
                            ->preload(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('No')
                    ->label('No')
                    ->getStateUsing(function ($record, $livewire, $column) {
                        return ($livewire->getTableRecords()->search($record) + 1);
                    }),

                Tables\Columns\TextColumn::make('kode_barang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama_barang')
                    ->searchable(),

                Tables\Columns\TextColumn::make('harga_beli')
                    ->label('Harga Beli')
                    ->formatStateUsing(function ($state, $record) {
                        return match ($record->mata_uang) {
                            'USD' => '$' . number_format($state, 2),
                            'EUR' => '€' . number_format($state, 2),
                            'JPY' => '¥' . number_format($state, 0),
                            'GBP' => '£' . number_format($state, 2),
                            'AUD' => 'A$' . number_format($state, 2),
                            'CAD' => 'C$' . number_format($state, 2),
                            'CHF' => 'CHF ' . number_format($state, 2),
                            'CNY' => '¥' . number_format($state, 2),
                            'SGD' => 'S$' . number_format($state, 2),
                            'HKD' => 'HK$' . number_format($state, 2),
                            'NZD' => 'NZ$' . number_format($state, 2),
                            'KRW' => '₩' . number_format($state, 0),
                            'INR' => '₹' . number_format($state, 2),
                            'THB' => '฿' . number_format($state, 2),
                            'MYR' => 'RM' . number_format($state, 2),
                            'PHP' => '₱' . number_format($state, 2),
                            'VND' => '₫' . number_format($state, 0),
                            'AED' => 'د.إ' . number_format($state, 2),
                            'SAR' => '﷼' . number_format($state, 2),
                            default => 'Rp ' . number_format($state, 0, ',', '.'),
                        };
                    }),

                Tables\Columns\TextColumn::make('harga_jual')
                    ->label('Harga Jual')
                    ->formatStateUsing(function ($state, $record) {
                        return match ($record->mata_uang) {
                            'USD' => '$' . number_format($state, 2),
                            'EUR' => '€' . number_format($state, 2),
                            'JPY' => '¥' . number_format($state, 0),
                            'GBP' => '£' . number_format($state, 2),
                            'AUD' => 'A$' . number_format($state, 2),
                            'CAD' => 'C$' . number_format($state, 2),
                            'CHF' => 'CHF ' . number_format($state, 2),
                            'CNY' => '¥' . number_format($state, 2),
                            'SGD' => 'S$' . number_format($state, 2),
                            'HKD' => 'HK$' . number_format($state, 2),
                            'NZD' => 'NZ$' . number_format($state, 2),
                            'KRW' => '₩' . number_format($state, 0),
                            'INR' => '₹' . number_format($state, 2),
                            'THB' => '฿' . number_format($state, 2),
                            'MYR' => 'RM' . number_format($state, 2),
                            'PHP' => '₱' . number_format($state, 2),
                            'VND' => '₫' . number_format($state, 0),
                            'AED' => 'د.إ' . number_format($state, 2),
                            'SAR' => '﷼' . number_format($state, 2),
                            default => 'Rp ' . number_format($state, 0, ',', '.'),
                        };
                    }),

                Tables\Columns\TextColumn::make('stok')
                    ->searchable(),

                Tables\Columns\TextColumn::make('satuan')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
        ];
    }
}
