<?php

namespace App\Filament\Resources\LaporanBulananResource\Pages;

use App\Filament\Resources\LaporanBulananResource;
use Filament\Resources\Pages\Page;

class LaporaBulanan extends Page
{
    protected static string $resource = LaporanBulananResource::class;
    protected static ?int $navigationSort = 20;
    protected static string $view = 'filament.resources.laporan-bulanan-resource.pages.lapora-bulanan';
}
