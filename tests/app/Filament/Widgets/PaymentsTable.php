<?php

namespace App\Filament\Widgets;

use App\Models\PaymentStats;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class PaymentsTable extends BaseWidget
{
    protected static ?string $heading = 'Σύνολα καταχωρημένων ενταλμάτων';
    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        $cacheKey = 'payment_stats_query';
        $ttl = now()->addMonths(2);

        // Check if the cache already has the query result.
        $cachedResult = Cache::remember($cacheKey, $ttl, function () {
            return PaymentStats::query()
                ->groupBy('year')
                ->selectRaw('MIN(id) as id, year, SUM(pdfs) AS total_pdfs, SUM(total_pages) AS total_pages')
                ->orderBy('year')
                ->get() // Execute the query and get the results
                ->toArray(); // Convert results to an array for caching
        });

        return PaymentStats::query()
            ->groupBy('year')
            ->selectRaw('MIN(id) as id, year, SUM(pdfs) AS total_pdfs, SUM(total_pages) AS total_pages')
            ->orderBy('year');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('year')->label('Έτος')->alignCenter(),
            Tables\Columns\TextColumn::make('total_pdfs')->label('Αριθμός ενταλμάτων άνα έτος')->alignCenter(),
            Tables\Columns\TextColumn::make('total_pages')->label('Αριθμός σελίδων άνα έτος')->alignCenter(),
        ];
    }
}
