<?php

namespace App\Filament\Resources\BarangJualResource\Pages;

use App\Filament\Resources\BarangJualResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangJual extends CreateRecord
{
    protected static string $resource = BarangJualResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Barang Jual Berhasil Dibuat';
    }
}
