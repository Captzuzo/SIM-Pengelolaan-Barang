<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Illuminate\Support\Facades\File;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $navigationLabel = 'Supplier';
    protected static ?string $slug = 'supplier';
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
                            ->searchable()
                            ->required()
                            ->options(fn() => collect(
                                json_decode(File::get(database_path('data/list_of_area/provinces.json')), true)
                            )->pluck('name', 'id')->toArray())
                            ->getSearchResultsUsing(function (string $search): array {
                                $json = File::get(database_path('data/list_of_area/provinces.json'));
                                $data = collect(json_decode($json, true));

                                // Tampilkan semua jika belum ada pencarian
                                if (trim($search) === '') {
                                    return $data->pluck('name', 'id')->toArray();
                                }

                                // Filter berdasarkan pencarian
                                return $data
                                    ->filter(fn($item) => str_contains(strtolower($item['name']), strtolower($search)))
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $json = File::get(database_path('data/list_of_area/provinces.json'));
                                $data = collect(json_decode($json, true));

                                return $data->firstWhere('id', $value)['name'] ?? null;
                            })
                            // ->reactive()
                            ->afterStateUpdated(fn($state, callable $set) => $set('regency_id', null)),

                        Select::make('regency_id')
                            ->label('Kabupaten/Kota')
                            ->required()
                            ->searchable()
                            // ->reactive()
                            ->options(function (callable $get) {
                                $provinceId = $get('province_id');
                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/regencies.json')), true)
                                )
                                    ->where('province_id', $provinceId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                $provinceId = $get('province_id');

                                if (!$provinceId) {
                                    return [];
                                }

                                $json = File::get(database_path('data/list_of_area/regencies.json'));
                                $data = collect(json_decode($json, true))
                                    ->where('province_id', $provinceId);

                                if (trim($search) !== '') {
                                    $data = $data->filter(
                                        fn($item) => str_contains(strtolower($item['name']), strtolower($search))
                                    );
                                }

                                return $data->pluck('name', 'id')->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $json = File::get(database_path('data/list_of_area/regencies.json'));
                                $data = collect(json_decode($json, true));

                                return $data->firstWhere('id', $value)['name'] ?? null;
                            })
                            ->afterStateUpdated(fn($state, callable $set) => $set('district_id', null)),

                        Select::make('district_id')
                            ->label('Kecamatan')
                            ->required()
                            ->searchable()
                            // ->reactive()
                            ->options(function (callable $get) {
                                $regencyId = $get('regency_id');

                                if (!$regencyId) {
                                    return [];
                                }

                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/districts.json')), true)
                                )
                                    ->where('regency_id', $regencyId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                $regencyId = $get('regency_id');

                                if (!$regencyId) {
                                    return [];
                                }

                                $data = collect(json_decode(File::get(database_path('data/list_of_area/districts.json')), true))
                                    ->where('regency_id', $regencyId);

                                if (trim($search) !== '') {
                                    $data = $data->filter(
                                        fn($item) => str_contains(strtolower($item['name']), strtolower($search))
                                    );
                                }

                                return $data->pluck('name', 'id')->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $data = collect(json_decode(File::get(database_path('data/list_of_area/districts.json')), true));
                                return $data->firstWhere('id', $value)['name'] ?? null;
                            })
                            ->afterStateUpdated(fn($state, callable $set) => $set('village_id', null)),


                        Select::make('village_id')
                            ->label('Desa/Kelurahan')
                            ->required()
                            ->searchable()
                            // ->reactive()
                            ->options(function (callable $get) {
                                $districtId = $get('district_id');

                                if (!$districtId) {
                                    return [];
                                }

                                return collect(
                                    json_decode(File::get(database_path('data/list_of_area/villages.json')), true)
                                )
                                    ->where('district_id', $districtId)
                                    ->pluck('name', 'id')
                                    ->toArray();
                            })
                            ->getSearchResultsUsing(function (string $search, callable $get): array {
                                $districtId = $get('district_id');

                                if (!$districtId) {
                                    return [];
                                }

                                $data = collect(json_decode(File::get(database_path('data/list_of_area/villages.json')), true))
                                    ->where('district_id', $districtId);

                                if (trim($search) !== '') {
                                    $data = $data->filter(
                                        fn($item) => str_contains(strtolower($item['name']), strtolower($search))
                                    );
                                }

                                return $data->pluck('name', 'id')->toArray();
                            })
                            ->getOptionLabelUsing(function ($value) {
                                $data = collect(json_decode(File::get(database_path('data/list_of_area/villages.json')), true));
                                return $data->firstWhere('id', $value)['name'] ?? null;
                            }),

                        Textarea::make('alamat_lengkap')
                            ->label('Alamat Lengkap')
                            ->required()
                            ->placeholder('Masukkan alamat lengkap pelanggan')
                            ->rows(2)
                            ->cols(2),

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
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
