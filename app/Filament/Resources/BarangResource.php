<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarangResource\Pages;
use App\Filament\Resources\BarangResource\RelationManagers;
use App\Models\Barang;
use App\Models\BarangBeli;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;
use Filament\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use App\Filament\Resources\BarangResource\Pages\ViewStok;

class BarangResource extends Resource
{
    protected static ?string $model = Barang::class;
    protected static ?string $navigationGroup = 'Manajemen Barang';
    protected static ?string $slug = 'barang';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

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
                            ->disabled(fn($record) => filled($record)) // Disable jika edit
                            ->afterStateUpdated(function ($state, callable $set) {
                                $kategori = Kategori::find($state);

                                if (!$kategori) return;

                                $kodeKategori = $kategori->kode_kategori; // kolom kode_kategori

                                // Hitung jumlah barang di kategori itu
                                $count = Barang::where('kategori_id', $kategori->id)->count() + 1;

                                // Format nomor 3 digit
                                $nomor = str_pad($count, 3, '0', STR_PAD_LEFT);

                                // Format kode_barang
                                $kodeBarang = "BRG-{$kodeKategori}-{$nomor}";

                                $set('kode_barang', $kodeBarang);
                            }),


                        Forms\Components\TextInput::make('nama_barang')
                            ->label('Nama Barang')
                            ->required(),


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
                            ->dehydrated(true) // <-- WAJIB, supaya ikut tersimpan walaupun disabled
                            ->disabled(fn($record) => true) // disable di create & edit
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
                            ->dehydrated(true) // <-- WAJIB, supaya ikut tersimpan walaupun disabled
                            ->disabled(fn($record) => true) // disable create & edit
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

                        // Stok
                        Forms\Components\TextInput::make('stok')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->dehydrated(true) // <-- WAJIB, supaya ikut tersimpan walaupun disabled
                            ->disabled(fn($record) => true), // disable create & edit


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
                    ->searchable()
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
                    ->searchable()
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


                // Tables\Columns\TextColumn::make('total_stok')
                //     ->label('Total Stok')
                //     ->getStateUsing(function ($record) {
                //         $total = $record->detailBarangBeli()->sum('stok');
                //         return number_format((float) $total, 0, ',', '.');
                //     })
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('stok')
                //     ->label('Stok')
                // Tables\Columns\TextColumn::make('total_stok')
                //     ->label('Stok')
                //     ->sortable(),

                Tables\Columns\TextColumn::make('stok')
                    ->label('Stok')
                    ->sortable()
                    ->default(0),

                // Tables\Columns\TextColumn::make('harga_satuan')
                //     ->label('Harga Satuan')
                //     ->formatStateUsing(function ($state, $record) {
                //         $value = is_numeric($state) ? $state : 0;
                //         return match ($record->mata_uang) {
                //             'USD' => '$' . number_format($value, 2),
                //             'EUR' => '€' . number_format($value, 2),
                //             'JPY' => '¥' . number_format($value, 0),
                //             'GBP' => '£' . number_format($value, 2),
                //             'AUD' => 'A$' . number_format($value, 2),
                //             'CAD' => 'C$' . number_format($value, 2),
                //             'CHF' => 'CHF ' . number_format($value, 2),
                //             'CNY' => '¥' . number_format($value, 2),
                //             'SGD' => 'S$' . number_format($value, 2),
                //             'HKD' => 'HK$' . number_format($value, 2),
                //             'NZD' => 'NZ$' . number_format($value, 2),
                //             'KRW' => '₩' . number_format($value, 0),
                //             'INR' => '₹' . number_format($value, 2),
                //             'THB' => '฿' . number_format($value, 2),
                //             'MYR' => 'RM' . number_format($value, 2),
                //             'PHP' => '₱' . number_format($value, 2),
                //             'VND' => '₫' . number_format($value, 0),
                //             'AED' => 'د.إ' . number_format($value, 2),
                //             'SAR' => '﷼' . number_format($value, 2),
                //             default => 'Rp ' . number_format($value, 0, ',', '.'),
                // //         };
                //     }),
            ])
            ->filters([
                //
            ])
            // ->headerActions([
            //     Action::make('refresh_data')
            //         ->label('Refresh Stok')
            //         ->icon('heroicon-o-arrow-path')
            //         ->action(function () {
            //             DB::transaction(function () {
            //                 $barangs = \App\Models\Barang::all();

            //                 foreach ($barangs as $barang) {
            //                     // Hitung stok masuk dari semua pembelian
            //                     $stokMasuk = $barang->detailBarangBeli()->sum('stok');

            //                     // Hitung stok keluar dari semua penjualan
            //                     $stokKeluar = $barang->detailPenjualan()->sum('qty');

            //                     // Stok akhir = masuk - keluar
            //                     $barang->stok = max(0, $stokMasuk - $stokKeluar);

            //                     // Update harga beli dari pembelian terbaru
            //                     $hargaBeliTerakhir = $barang->detailBarangBeli()
            //                         ->latest()
            //                         ->value('harga_satuan');
            //                     if ($hargaBeliTerakhir !== null) {
            //                         $barang->harga_beli = $hargaBeliTerakhir;
            //                     }

            //                     // Update harga jual dari penjualan terbaru
            //                     $hargaJualTerakhir = $barang->detailPenjualan()
            //                         ->latest()
            //                         ->value('harga_satuan');
            //                     if ($hargaJualTerakhir !== null) {
            //                         $barang->harga_jual = $hargaJualTerakhir;
            //                     }

            //                     $barang->save();
            //                 }
            //             });

            //             Notification::make()
            //                 ->title('Stok berhasil dihitung ulang dari riwayat!')
            //                 ->success()
            //                 ->send();
            //         })
            //         ->requiresConfirmation()
            //         ->color('success'),
            // ])
            // ->headerActions([
            //     Action::make('refresh_data')
            //         ->label('Refresh Stok & Harga')
            //         ->icon('heroicon-o-arrow-path')
            //         ->action(function () {
            //             DB::transaction(function () {
            //                 $barangs = \App\Models\Barang::all();

            //                 foreach ($barangs as $barang) {
            //                     // Hitung stok masuk (dari stok_barangs)
            //                     $stokMasuk = \App\Models\StokBarang::where('barang_id', $barang->id)->sum('stok');

            //                     // Hitung stok keluar (dari penjualan)
            //                     $stokKeluar = $barang->detailPenjualan()->sum('qty');

            //                     // Update stok
            //                     $barang->stok = max(0, $stokMasuk - $stokKeluar);

            //                     // Harga beli terakhir dari stok terbaru
            //                     $hargaBeliTerakhir = \App\Models\StokBarang::where('barang_id', $barang->id)
            //                         ->latest('tanggal_beli')
            //                         ->value('harga_beli');
            //                     if ($hargaBeliTerakhir) {
            //                         $barang->harga_beli = $hargaBeliTerakhir;
            //                     }

            //                     // Harga jual terakhir dari penjualan terbaru
            //                     $hargaJualTerakhir = $barang->detailPenjualan()
            //                         ->latest('created_at')
            //                         ->value('harga_jual');
            //                     if ($hargaJualTerakhir) {
            //                         $barang->harga_jual = $hargaJualTerakhir;
            //                     }

            //                     $barang->save();
            //                 }
            //             });

            //             Notification::make()
            //                 ->title('Stok & harga berhasil diperbarui!')
            //                 ->success()
            //                 ->send();
            //         })
            //         ->requiresConfirmation()
            //         ->color('success'),
            // ])

            // ->headerActions([
            //     Action::make('refresh_data')
            //         ->label('Refresh Stok & Harga')
            //         ->icon('heroicon-o-arrow-path')
            //         ->action(function () {
            //             DB::transaction(function () {
            //                 $barangs = \App\Models\Barang::with(['detailBarangBeli', 'detailPenjualan', 'barangJual'])->get();

            //                 foreach ($barangs as $barang) {
            //                     // Hitung stok masuk & keluar
            //                     $stokMasuk = $barang->detailBarangBeli->sum('stok');
            //                     $stokKeluar = $barang->detailPenjualan->sum('qty');

            //                     // Update stok
            //                     $barang->stok = max(0, $stokMasuk - $stokKeluar);

            //                     // Harga beli terakhir
            //                     $hargaBeliTerakhir = $barang->detailBarangBeli()
            //                         ->latest('created_at')
            //                         ->value('harga_satuan');
            //                     if ($hargaBeliTerakhir) {
            //                         $barang->harga_beli = $hargaBeliTerakhir;
            //                     }

            //                     // Harga jual terakhir
            //                     $hargaJualTerakhir = $barang->barangJual()
            //                         ->latest('created_at')
            //                         ->value('harga_jual');
            //                     if ($hargaJualTerakhir) {
            //                         $barang->harga_jual = $hargaJualTerakhir;
            //                     }

            //                     $barang->save();
            //                 }
            //             });

            //             Notification::make()
            //                 ->title('Stok & harga berhasil diperbarui dari riwayat!')
            //                 ->success()
            //                 ->send();
            //         })
            //         ->requiresConfirmation()
            //         ->color('success'),
            // ])



            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => \App\Filament\Resources\BarangResource\Pages\ViewStok::getUrl(['record' => $record->id]))
                    ->label('Stok'),
                // Tables\Actions\EditAction::make(),
                Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton(),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->iconButton(),
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
            RelationManagers\StokBarangsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarangs::route('/'),
            'create' => Pages\CreateBarang::route('/create'),
            'edit' => Pages\EditBarang::route('/{record}/edit'),
            'view-stok' => Pages\ViewStok::route('/{record}/view'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withSum('stokBarangs', 'stok_sisa');
    }
}
