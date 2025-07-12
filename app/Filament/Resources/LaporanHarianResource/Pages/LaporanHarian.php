<?php

namespace App\Filament\Pages\Laporan;;

use App\Filament\Resources\LaporanHarianResource;
use Filament\Pages\Page;
// use Filament\Resources\Pages\Page;

class LaporanHarianPage extends Page
{
    // protected static string $resource = Lapo::class;
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $title = 'Laporan Harian';
    protected static ?string $slug = 'laporan-harian';

    protected static string $view = 'filament.pages.laporan-harian';
}