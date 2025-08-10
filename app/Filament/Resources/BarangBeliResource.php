<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangBeliResource\Pages;
use App\Models\Barang;
use App\Models\BarangBeli;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BarangBeliResource extends Resource
{
    protected static ?string $model = BarangBeli::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $navigationLabel = 'Barang Beli';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('user_id')
                            ->label('Kasir')
                            ->default(auth()->id())
                            ->disabled()
                            ->dehydrated()
                            ->formatStateUsing(fn() => auth()->user()?->name)
                            ->dehydrateStateUsing(fn() => auth()->id()),

                        TextInput::make('no_invoice')
                            ->label('No Invoice')
                            ->disabled()
                            ->columnSpan(2)
                            ->dehydrated()
                            ->unique(ignoreRecord: true),

                        Select::make('supplier_id')
                            ->label('Supplier')
                            ->relationship('supplier', 'nama')
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->columnSpan(2)
                            ->preload()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $supplier = \App\Models\Supplier::find($state);
                                if ($supplier) {
                                    $tanggal = now()->format('dmY');

                                    // Ganti ke model BarangBeli atau model pembelian yang benar
                                    $countToday = \App\Models\BarangBeli::whereDate('created_at', now()->toDateString())->count();

                                    $urut = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
                                    $nama = strtoupper(str_replace(' ', '', $supplier->nama));

                                    $set('no_invoice', "BB-{$urut}-{$nama}-{$tanggal}");
                                }
                            }),
                        Repeater::make('detailBarangBeli')
                            ->relationship('detailBarangBeli')
                            ->schema([
                                Select::make('barang_id')
                                    ->label('Barang')
                                    ->relationship('barang', 'nama_barang')
                                    // ->options(Barang::all()->pluck('nama_barang', 'id'))
                                    ->options(function (callable $get) {
                                        // Ambil semua barang
                                        $allBarangs = \App\Models\Barang::all();

                                        // Ambil semua barang_id yang sudah dipilih pada repeater
                                        $selectedBarangIds = collect($get('../../detailBarangBeli')) // naik 2 level dari barang_id
                                            ->pluck('barang_id')
                                            ->filter(); // buang null

                                        // Filter barang yang belum dipilih
                                        return $allBarangs
                                            ->reject(fn($barang) => $selectedBarangIds->contains($barang->id))
                                            ->pluck('nama_barang', 'id');
                                    })
                                    ->required()
                                    ->searchable(),
                                TextInput::make('stok')
                                    ->label('Jumlah')
                                    ->numeric()
                                    ->required(),
                                TextInput::make('harga_satuan')
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
                                    ->afterStateHydrated(fn($state, callable $set) => $set('harga_jual', number_format($state, 0, ',', '.'))),
                                // TextInput::make('harga_satuan')
                                //     ->label('Harga Beli')
                                //     ->numeric()
                                //     ->required(),
                            ])
                            ->columns(3)
                            ->required(),

                        DatePicker::make('tanggal_beli')
                            ->required()
                            ->columnSpan(2),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('kasir.name')
                    ->label('Kasir')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('no_invoice')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('supplier.nama')
                    ->label('Supplier')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_beli')
                    ->date('d-m-Y'),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('invoice-barangBeli')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn(BarangBeli $record) => route('barangBeli.barang-beli', $record->id))
                    ->openUrlInNewTab(),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangBelis::route('/'),
            'create' => Pages\CreateBarangBeli::route('/create'),
            'edit' => Pages\EditBarangBeli::route('/{record}/edit'),
        ];
    }
}
