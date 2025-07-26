<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenjualanResource\Pages;
use App\Filament\Resources\PenjualanResource\RelationManagers;
use App\Models\Barang;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Notifications\Notification;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Get;
use Filament\Forms\Components\Hidden;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Penjualan';
    protected static ?string $slug = 'penjualan';
    protected static ?int $navigationSort = 11;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Hidden::make('user_id')
                            ->default(fn() => auth()->id()),

                        TextInput::make('kasir')
                            ->default(fn() => auth()->user()?->name)
                            ->label('Kasir')
                            ->disabled()
                            ->columnSpan(2)
                            ->dehydrated(false), // jangan simpan ke DB
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

                        Repeater::make('details')
                            ->label('Detail Penjualan')
                            ->relationship('details')
                            ->schema([
                                Select::make('barang_id')
                                    ->label('Barang')
                                    ->relationship('barang', 'nama_barang')
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->preload()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($barang = \App\Models\Barang::find($state)) {
                                            $set('harga_satuan', $barang->harga_jual);
                                            $set('qty', 1);
                                            $set('subtotal', $barang->harga_jual);
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
                                            notification()->danger('Stok tidak mencukupi, stok tersedia: ' . $barang->stok);
                                        }
                                        $set('subtotal', (int)$state * (int)$get('harga_satuan'));
                                    }),

                                TextInput::make('harga_satuan')
                                    ->label('Harga Satuan')
                                    ->numeric()
                                    ->required()
                                    ->reactive()
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
                                $details = collect($get('details'));
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
                                $details = collect($get('details'))
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
                            ->formatStateUsing(fn($state) => 'Rp ' . number_format(max(0, $state), 0, ',', '.'))
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

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sisa'] = max(0, (int) $data['total'] - (int) $data['bayar']);
        $data['kembalian'] = max(0, (int) $data['bayar'] - (int) $data['total']);
        $data['status_pembayaran'] = $data['bayar'] >= $data['total'] ? 'lunas' : 'belum bayar';

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        $data['sisa'] = max(0, (int) $data['total'] - (int) $data['bayar']);
        $data['kembalian'] = max(0, (int) $data['bayar'] - (int) $data['total']); // ✅ Tambahkan ini
        $data['status_pembayaran'] = $data['bayar'] >= $data['total'] ? 'lunas' : 'belum bayar';
        return $data;
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

                // Tables\Columns\TextColumn::make('sisa')
                //     ->money('IDR', true),

                // Tables\Columns\TextColumn::make('kembalian')
                //     ->money('IDR', true),

                BadgeColumn::make('status_pembayaran')
                    ->colors([
                        'success' => 'lunas',
                        'danger' => 'belum bayar',
                    ])
                    ->formatStateUsing(fn($state) => ucfirst($state)),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d-m-Y H:i'),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status_pembayaran')
                    ->label('Status Pembayaran')
                    ->options([
                        'lunas' => 'Lunas',
                        'belum bayar' => 'Belum Bayar',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('invoice-penjualan')
                    ->label('Cetak')
                    ->icon('heroicon-o-printer')
                    ->url(fn(Penjualan $record) => route('penjualan.invoice-penjualan', $record->id))
                    ->openUrlInNewTab(),
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
            // RelationManagers\DetailPenjualansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}