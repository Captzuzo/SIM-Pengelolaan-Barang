<?php

namespace App\Filament\Resources\BarangBeliResource\Pages;

use App\Filament\Resources\BarangBeliResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangBeli extends EditRecord
{
    protected static string $resource = BarangBeliResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getSavedNotificationTitle(): ?string
    {
        return 'Barang Beli Berhasil Diperbarui';
    }
}
