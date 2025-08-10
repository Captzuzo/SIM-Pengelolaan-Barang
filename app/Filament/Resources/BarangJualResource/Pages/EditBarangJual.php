<?php

namespace App\Filament\Resources\BarangJualResource\Pages;

use App\Filament\Resources\BarangJualResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBarangJual extends EditRecord
{
    protected static string $resource = BarangJualResource::class;

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
        return 'Barang Jual Berhasil Diperbarui';
    }
}
