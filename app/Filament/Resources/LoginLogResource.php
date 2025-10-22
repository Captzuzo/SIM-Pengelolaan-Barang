<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\LoginLog;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Resources\LoginLogResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\LoginLogResource\RelationManagers;

class LoginLogResource extends Resource
{
    protected static ?string $model = LoginLog::class;
    protected static ?string $navigationGroup = 'Setting';
    protected static ?string $slug = 'loginlog';
    protected static ?int $navigationSort = 99;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('no')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->sortable()
                    ->color(fn($state) => match ($state) {
                        'success' => 'success',
                        'logout' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->copyable(),

                TextColumn::make('location')
                    ->label('Lokasi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('latitude')
                    ->label('Lat')
                    ->sortable()
                    ->visibleFrom('md'),

                TextColumn::make('longitude')
                    ->label('Long')
                    ->sortable()
                    ->visibleFrom('md'),

                TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(30)
                    ->tooltip(fn($record) => $record->user_agent),

                TextColumn::make('logged_at')
                    ->label('Waktu Login')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('message')
                    ->label('Pesan')
                    ->wrap()
                    ->limit(50),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'logout' => 'Logout',
                        'failed' => 'Failed',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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
            'index' => Pages\ListLoginLogs::route('/'),
            // 'create' => Pages\CreateLoginLog::route('/create'),
            // 'edit' => Pages\EditLoginLog::route('/{record}/edit'),
        ];
    }
}
