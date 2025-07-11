<?php

namespace App\Filament\Resources\PelangganHutangResource\Pages;

use App\Filament\Resources\PelangganHutangResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPelangganHutangs extends ListRecords
{
    protected static string $resource = PelangganHutangResource::class;

    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
}