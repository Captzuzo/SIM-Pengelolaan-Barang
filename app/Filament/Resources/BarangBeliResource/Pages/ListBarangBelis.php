<?php

namespace App\Filament\Resources\BarangBeliResource\Pages;

use App\Filament\Resources\BarangBeliResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangBelis extends ListRecords
{
    protected static string $resource = BarangBeliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
