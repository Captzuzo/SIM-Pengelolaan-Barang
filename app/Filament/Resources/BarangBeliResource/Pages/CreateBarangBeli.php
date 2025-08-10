<?php

namespace App\Filament\Resources\BarangBeliResource\Pages;

use App\Filament\Resources\BarangBeliResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarangBeli extends CreateRecord
{
    protected static string $resource = BarangBeliResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Barang Beli Berhasil Dibuat';
    }
}
