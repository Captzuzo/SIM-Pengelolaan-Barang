<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PelangganResource\Pages;
use App\Filament\Resources\PelangganResource\RelationManagers;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationIcon = 'heroicon-o-User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required(),
                        Forms\Components\TextInput::make('no_hp')
                            ->numeric()
                            ->required(),

                        Select::make('provinces')
                            ->label('Provinsi')
                            ->required()
                            ->options(\App\Models\Province::pluck('name', 'id'))
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('regencies', null)),

                        Select::make('regencies')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->options(
                                fn(callable $get) =>
                                \App\Models\Regency::where('province_id', $get('provinces'))->pluck('name', 'id')
                            )
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('districts', null)),

                        Select::make('districts')
                            ->label('Kecamatan')
                            ->required()
                            ->options(
                                fn(callable $get) =>
                                \App\Models\District::where('regency_id', $get('regencies'))->pluck('name', 'id')
                            )
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('villages', null)),

                        Select::make('villages')
                            ->label('Desa')
                            ->required()
                            ->options(
                                fn(callable $get) =>
                                \App\Models\Village::where('district_id', $get('districts'))->pluck('name', 'id')
                            )
                            ->searchable(),

                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province.name')
                    ->label('Provinsi')
                    ->searchable(),

                Tables\Columns\TextColumn::make('regency.name')
                    ->label('Kabupaten')
                    ->searchable(),

                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->searchable(),

                Tables\Columns\TextColumn::make('village.name')
                    ->label('Desa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat_lengkap'),
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
            ])
            ->query(
                Pelanggan::with(['province', 'regency', 'district', 'village'])
            )
            ->modifyQueryUsing(function (Builder $query) {
                // ← Di sinilah kamu bisa menggunakan Eloquent\Builder
                $query->with(['province', 'regency', 'district', 'village']);
            });
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
            'index' => Pages\ListPelanggans::route('/'),
            'create' => Pages\CreatePelanggan::route('/create'),
            'edit' => Pages\EditPelanggan::route('/{record}/edit'),
        ];
    }
}