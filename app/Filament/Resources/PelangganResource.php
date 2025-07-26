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
                            ->numeric()
                            ->required(),

                        // PROVINSI
                        Select::make('provinces')
                            ->label('Provinsi')
                            ->required()
                            ->options(function () {
                                $json = json_decode(File::get(database_path('data/list_of_area/provinces.json')), true);
                                return collect($json)->pluck('name', 'id')->toArray();
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search): array {
                                $data = json_decode(File::get(database_path('data/list_of_area/provinces.json')), true);
                                return collect($data)
                                    ->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)))
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn($value) => collect(
                                json_decode(File::get(database_path('data/list_of_area/provinces.json')), true)
                            )->firstWhere('id', $value)['name'] ?? null)
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('regencies', null)),

                        // KABUPATEN/KOTA
                        Select::make('regencies')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->options(function (callable $get) {
                                $provinceId = $get('provinces');
                                if (!$provinceId) return [];
                                $json = json_decode(File::get(database_path('data/list_of_area/regencies.json')), true);
                                return collect($json)
                                    ->where('province_id', $provinceId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $json = json_decode(File::get(database_path('data/list_of_area/regencies.json')), true);
                                return collect($json)
                                    ->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)))
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn($value) => collect(
                                json_decode(File::get(database_path('data/list_of_area/regencies.json')), true)
                            )->firstWhere('id', $value)['name'] ?? null)
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('districts', null)),

                        // KECAMATAN
                        Select::make('districts')
                            ->label('Kecamatan')
                            ->required()
                            ->options(function (callable $get) {
                                $regencyId = $get('regencies');
                                if (!$regencyId) return [];
                                $json = json_decode(File::get(database_path('data/list_of_area/districts.json')), true);
                                return collect($json)
                                    ->where('regency_id', $regencyId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $json = json_decode(File::get(database_path('data/list_of_area/districts.json')), true);
                                return collect($json)
                                    ->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)))
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn($value) => collect(
                                json_decode(File::get(database_path('data/list_of_area/districts.json')), true)
                            )->firstWhere('id', $value)['name'] ?? null)
                            ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('villages', null)),

                        // DESA
                        Select::make('villages')
                            ->label('Desa')
                            ->required()
                            ->options(function (callable $get) {
                                $districtId = $get('districts');
                                if (!$districtId) return [];
                                $json = json_decode(File::get(database_path('data/list_of_area/villages.json')), true);
                                return collect($json)
                                    ->where('district_id', $districtId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->searchable()
                            ->getSearchResultsUsing(function (string $search) {
                                $json = json_decode(File::get(database_path('data/list_of_area/villages.json')), true);
                                return collect($json)
                                    ->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)))
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(fn($value) => collect(
                                json_decode(File::get(database_path('data/list_of_area/villages.json')), true)
                            )->firstWhere('id', $value)['name'] ?? null),

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
