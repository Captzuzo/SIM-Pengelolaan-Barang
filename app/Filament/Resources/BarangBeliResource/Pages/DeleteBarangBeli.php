<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use App\Filament\Resources\BarangBeliResource;
use App\Filament\Resources\PenjualanResource;
use App\Models\Barang;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class DeleteBarangBeli extends EditRecord
{

    protected static string $resource = BarangBeliResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Barang Beli Berhasil Dihapus';
    }
}
