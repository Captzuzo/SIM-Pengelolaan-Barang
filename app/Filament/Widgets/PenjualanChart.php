<?php

namespace App\Filament\Widgets;

use App\Models\Penjualan;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class PenjualanChart extends ChartWidget
{
    protected static ?string $heading = 'Grafik Penjualan Harian';
    protected static ?string $title = 'Dashboard';
    protected static ?int $sort = 1;

    protected function getData(): array
    {
        $data = Penjualan::select(
            DB::raw('DATE(tanggal) as tanggal'),
            DB::raw('SUM(total) as total')
        )
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->limit(7) // tampilkan 7 hari terakhir
            ->get();

        $labels = $data->pluck('tanggal')->map(fn($tgl) => date('d M', strtotime($tgl)))->toArray();
        $values = $data->pluck('total')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Total Penjualan',
                    'data' => $values,
                    'fill' => 'true',
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.4)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
