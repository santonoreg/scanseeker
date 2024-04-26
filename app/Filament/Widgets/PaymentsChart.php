<?php

namespace App\Filament\Widgets;

use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;

class PaymentsChart extends ChartWidget
{
    protected static ?string $heading = 'Ψηφιοποιημένα αρχεία άνα έτος';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $maxHeight = '250px';

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Ψηφιοποιήσεις',
                    'data' => [575, 1096, 1005, 1035, 1201],
                    'fill' => 'start',
                ],
            ],
            'labels' => ['2011', '2012', '2013', '2014', '2015'],
        ];
    }
}
