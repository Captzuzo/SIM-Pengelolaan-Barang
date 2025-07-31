<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriResource\Pages;
use App\Filament\Resources\KategoriResource\RelationManagers;
use App\Models\Kategori;
use Filament\Forms;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KategoriResource extends Resource
{
    protected static ?string $model = Kategori::class;
    protected static ?string $navigationGroup = 'Master';
    protected static ?string $slug = 'kategori';
    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('kode_kategori')
                            ->label('Kode Kategori')
                            ->readOnly()
                            ->maxLength(255),

                        TextInput::make('nama_kategori')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255)
                            ->unique()
                            ->live(onBlur: true) // agar hanya update saat blur
                            ->afterStateUpdated(function (string $state, callable $set) {
                                $words = preg_split('/\s+/', trim($state));
                                $code = '';

                                if (count($words) === 1) {
                                    $code = strtoupper(substr($words[0], 0, 3));
                                } elseif (count($words) === 2) {
                                    $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 2));
                                } else {
                                    $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1));
                                }

                                $set('kode_kategori', $code);
                            }),
                    ]),
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
                Tables\Columns\TextColumn::make('kode_kategori')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_kategori')
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
            'index' => Pages\ListKategoris::route('/'),
            'create' => Pages\CreateKategori::route('/create'),
            'edit' => Pages\EditKategori::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return self::generateKodeKategori($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return self::generateKodeKategori($data);
    }

    protected static function generateKodeKategori(array $data): array
    {
        $nama = $data['nama_kategori'] ?? '';
        $words = preg_split('/\s+/', trim($nama));
        $code = '';

        if (count($words) === 1) {
            $code = strtoupper(substr($words[0], 0, 3));
        } elseif (count($words) === 2) {
            $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 2));
        } else {
            $code = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr($words[2], 0, 1));
        }

        $data['kode_kategori'] = $code;
        return $data;
    }
}