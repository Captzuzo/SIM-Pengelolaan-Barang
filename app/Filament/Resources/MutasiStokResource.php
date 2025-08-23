<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MutasiStokResource\Pages;
use App\Models\MutasiStok;
use App\Models\Barang;
use App\Models\MutasiStokModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MutasiStokResource extends Resource
{
    protected static ?string $model = MutasiStokModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $navigationLabel = 'Mutasi Stok';
    protected static ?string $pluralModelLabel = 'Mutasi Stok';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('barang_id')
                //     ->label('Barang')
                //     ->relationship('barang', 'nama_barang')
                //     ->searchable()
                //     ->required(),

                // Forms\Components\Select::make('jenis_mutasi')
                //     ->label('Jenis Mutasi')
                //     ->options([
                //         'masuk' => 'Stok Masuk',
                //         'keluar' => 'Stok Keluar',
                //     ])
                //     ->required(),

                // Forms\Components\TextInput::make('qty')
                //     ->label('Jumlah')
                //     ->numeric()
                //     ->required(),

                // Forms\Components\TextInput::make('harga')
                //     ->label('Harga')
                //     ->numeric()
                //     ->prefix('Rp')
                //     ->default(0),

                // Forms\Components\Textarea::make('keterangan')
                //     ->label('Keterangan')
                //     ->rows(3)
                //     ->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('barang.nama_barang')
                    ->label('Barang')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\BadgeColumn::make('tipe')
                    ->label('Jenis')
                    ->colors([
                        'success' => 'masuk',
                        'danger' => 'keluar',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Qty')
                    ->sortable(),

                Tables\Columns\TextColumn::make('harga_beli')
                    ->label('Harga')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(30),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tipe')
                    ->options([
                        'masuk' => 'Stok Masuk',
                        'keluar' => 'Stok Keluar',
                    ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMutasiStoks::route('/'),
            // 'create' => Pages\CreateMutasiStok::route('/create'),
            // 'edit' => Pages\EditMutasiStok::route('/{record}/edit'),
        ];
    }
}
