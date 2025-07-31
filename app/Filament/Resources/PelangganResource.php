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
use Illuminate\Support\Facades\File;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Pelanggan';
    protected static ?string $slug = 'pelanggan';
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
                            ->minLength(11)
                            ->maxLength(12)
                            ->numeric()
                            ->required(),

                        Select::make('province_id')
                            ->label('Provinsi')
                            ->required()
                            ->options(fn() => collect(
                                json_decode(File::get(database_path('data/list_of_area/provinces.json')), true)
                            )->pluck('name', 'id')->toArray())
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('regency_id', null)),

                        Select::make('regency_id')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->options(function (callable $get) {
                                $provinceId = $get('province_id');
                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/regencies.json')), true)
                                )
                                    ->where('province_id', $provinceId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('district_id', null)),

                        Select::make('district_id')
                            ->label('Kecamatan')
                            ->required()
                            ->options(function (callable $get) {
                                $regencyId = $get('regency_id');
                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/districts.json')), true)
                                )
                                    ->where('regency_id', $regencyId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('village_id', null)),

                        Select::make('village_id')
                            ->label('Desa/Kelurahan')
                            ->required()
                            ->options(function (callable $get) {
                                $districtId = $get('district_id');
                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/villages.json')), true)
                                )
                                    ->where('district_id', $districtId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable(),

                        Forms\Components\Textarea::make('alamat_lengkap')
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Pelanggan::query())
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_hp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('province_id')
                    ->label('Provinsi')
                    ->formatStateUsing(function ($state) {
                        $data = File::exists(database_path('data/list_of_area/provinces.json'))
                            ? json_decode(File::get(database_path('data/list_of_area/provinces.json')), true)
                            : [];

                        return collect($data)->firstWhere('id', $state)['name'] ?? '-';
                    }),

                Tables\Columns\TextColumn::make('regency_id')
                    ->label('Kabupaten/Kota')
                    ->formatStateUsing(function ($state) {
                        $data = File::exists(database_path('data/list_of_area/regencies.json'))
                            ? json_decode(File::get(database_path('data/list_of_area/regencies.json')), true)
                            : [];

                        return collect($data)->firstWhere('id', $state)['name'] ?? '-';
                    }),

                Tables\Columns\TextColumn::make('district_id')
                    ->label('Kecamatan')
                    ->formatStateUsing(function ($state) {
                        $data = File::exists(database_path('data/list_of_area/districts.json'))
                            ? json_decode(File::get(database_path('data/list_of_area/districts.json')), true)
                            : [];

                        return collect($data)->firstWhere('id', $state)['name'] ?? '-';
                    }),

                Tables\Columns\TextColumn::make('village_id')
                    ->label('Desa/Kelurahan')
                    ->formatStateUsing(function ($state) {
                        $data = File::exists(database_path('data/list_of_area/villages.json'))
                            ? json_decode(File::get(database_path('data/list_of_area/villages.json')), true)
                            : [];

                        return collect($data)->firstWhere('id', $state)['name'] ?? '-';
                    }),
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
            ]);
        // ->query(
        //     Pelanggan::with(['province_id', 'regency_id', 'district_id', 'village_id'])
        // )
        // ->modifyQueryUsing(function (Builder $query) {
        //     // ← Di sinilah kamu bisa menggunakan Eloquent\Builder
        //     $query->with(['province_id', 'regency_id', 'district_id', 'village_id']);
        // });
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
