<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Set;
use App\Models\PelangganHutang;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PelangganHutangResource\Pages;
use App\Filament\Resources\PelangganHutangResource\RelationManagers;


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

                        Select::make('pelanggan_id')
                            ->label('Pelanggan')
                            ->relationship('pelanggan', 'nama')
                            ->required()
                            ->searchable()
                            ->reactive()
                            ->columnSpan(2)
                            ->preload()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $pelanggan = \App\Models\Pelanggan::find($state);
                                if ($pelanggan) {
                                    $tanggal = now()->format('dmY');
                                    $countToday = \App\Models\Penjualan::whereDate('created_at', now()->toDateString())->count();
                                    $urut = str_pad($countToday + 1, 3, '0', STR_PAD_LEFT);
                                    $nama = strtoupper(str_replace(' ', '', $pelanggan->nama));
                                    $set('no_invoice', 'PJL-' . $urut . '-' . $nama . '-' . $tanggal);
                                }
                            }),

                        DatePicker::make('tanggal')
                            ->label('Tanggal')
                            ->default(now())
                            ->columnSpan(2)
                            ->required(),

                        Repeater::make('detail')
                            ->label('Detail Penjualan')
                            ->relationship('detail')
                            ->schema([
                                Select::make('barang_id')
                                    ->label('Barang')
                                    ->relationship('barang', 'nama_barang')
                                    ->options(function (callable $get) {
                                        // Ambil semua barang
                                        $allBarangs = \App\Models\Barang::all();
                                        // $allBarangs = \App\Models\Barang::Where('')

                                        // Ambil semua barang_id yang sudah dipilih pada repeater
                                        $selectedBarangIds = collect($get('../../detail')) // naik 2 level dari barang_id
                                            ->pluck('barang_id')
                                            ->filter(); // buang null

                                        // Filter barang yang belum dipilih
                                        return $allBarangs
                                            ->reject(fn($barang) => $selectedBarangIds->contains($barang->id))
                                            ->pluck('nama_barang', 'id');
                                    })
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->preload()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        //     $barang = \App\Models\Barang::with('barangJual')->find($state);
                                        //     if ($barang && $barang->barangJual) {
                                        //         $set('harga_satuan', $barang->barangJual->harga_jual);
                                        //         $set('qty', 1);
                                        //         $set('subtotal', $barang->barangJual->harga_jual);
                                        //     } else {
                                        //         $set('harga_satuan', 0);
                                        //         $set('qty', 1);
                                        //         $set('subtotal', 0);
                                        //     }
                                        // }),

                                        $barang = \App\Models\Barang::with('barangJual')->find($state);

                                        if ($barang) {
                                            // ✅ Cek stok 0
                                            if ($barang->stok <= 0) {
                                                $set('qty', 0);
                                                $set('harga_satuan', 0);
                                                $set('subtotal', 0);

                                                \Filament\Notifications\Notification::make()
                                                    ->title('Penjualan tidak bisa')
                                                    ->body('Stok barang "' . $barang->nama_barang . '" kosong.')
                                                    ->danger()
                                                    ->send();

                                                return; // hentikan proses
                                            }

                                            // Set harga & qty default kalau stok tersedia
                                            if ($barang->barangJual) {
                                                $set('harga_satuan', $barang->barangJual->harga_jual);
                                                $set('qty', 1);
                                                $set('subtotal', $barang->barangJual->harga_jual);
                                            } else {
                                                $set('harga_satuan', 0);
                                                $set('qty', 1);
                                                $set('subtotal', 0);
                                            }
                                        }
                                    }),
                                TextInput::make('qty')
                                    ->label('Qty')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $barang = \App\Models\Barang::find($get('barang_id'));
                                        if ($barang && $state > $barang->stok) {
                                            $set('qty', $barang->stok);
                                            Notification::make()
                                                ->title('Stok tidak mencukupi')
                                                ->body('Stok tersedia: ' . $barang->stok)
                                                ->danger()
                                                ->send();
                                        }
                                        $set('subtotal', (int)$state * (int)$get('harga_satuan'));
                                    }),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
                                    ->disabled()
                                    ->dehydrated()
                                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                        $set('subtotal', (int)$get('qty') * (int)$state);
                                    }),

                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),
                            ])
                            ->columns(4)
                            ->columnSpan(2)
                            ->reactive()
                            ->afterStateUpdated(function (callable $get, callable $set) {
                                $details = collect($get('detail'));
                                $total = $details->sum('subtotal');
                                $set('total', $total);
                            })
                            ->createItemButtonLabel('Tambah Barang'),

                        TextInput::make('total')
                            ->label('Total')
                            ->numeric()
                            ->required()
                            ->reactive()
                            ->disabled()
                            ->dehydrated()
                            ->placeholder(function (Set $set, Get $get) {
                                $details = collect($get('detail'))
                                    ->pluck('subtotal')->sum();
                                if ($details == null) {
                                    $set('total', 0);
                                } else {
                                    $set('total', $details);
                                }
                            }),

                        TextInput::make('bayar')
                            ->label('Bayar')
                            ->numeric()
                            ->required()
                            ->columnSpan(2)
                            ->minValue(0)
                            ->reactive()
                            ->debounce(1000)
                            ->afterStateUpdated(function ($state, callable $get, callable $set) {
                                $total = (int) $get('total');
                                $bayar = (int) $state;

                                if ($bayar < $total) {
                                    $set('sisa', $total - $bayar);
                                    $set('kembalian', 0);
                                    $set('status_pembayaran', 'belum bayar');
                                } elseif ($bayar > $total) {
                                    $set('sisa', 0);
                                    $set('kembalian', $bayar - $total);
                                    $set('status_pembayaran', 'lunas');
                                } else {
                                    $set('sisa', 0);
                                    $set('kembalian', 0);
                                    $set('status_pembayaran', 'lunas');
                                }
                            }),

                        TextInput::make('kembalian')
                            ->label('Kembalian')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(2)
                            ->visible(fn(callable $get) => (int) $get('bayar') >= (int) $get('total'))
                            ->dehydrated(),

                        TextInput::make('sisa')
                            ->label('Piutang')
                            ->numeric()
                            ->disabled()
                            ->columnSpan(2)
                            // ->formatStateUsing(fn($state) => 'Rp ' . number_format(max(0, $state), 0, ',', '.'))
                            // ->formatStateUsing(fn($state) => max(0, $state))
                            ->visible(fn(callable $get) => (int) $get('bayar') <= (int) $get('total'))
                            ->dehydrated(),

                        TextInput::make('status_pembayaran')
                            ->label('Status Pembayaran')
                            ->default('belum bayar')
                            ->dehydrated()
                            ->rules(['in:lunas,belum bayar'])
                            ->disabled(),
                    ])
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
                    ->label('Piutang')
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
