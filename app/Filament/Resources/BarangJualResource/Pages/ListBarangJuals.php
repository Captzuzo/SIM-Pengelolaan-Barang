<?php

namespace App\Filament\Resources\BarangJualResource\Pages;

use App\Filament\Resources\BarangJualResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBarangJuals extends ListRecords
{
    protected static string $resource = BarangJualResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
