<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use pxlrbt\FilamentExcel\Columns\Column;
use pxlrbt\FilamentExcel\Exports\ExcelExport;
use pxlrbt\FilamentExcel\Actions\Pages\ExportAction;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;
    protected static ?string $title = ' '; // remove page title

    public function getTabs():array {
        return [
            'all' => Tab::make('Όλα τα έτη'),
            '2011' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('year', 2011)),
            '2012' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('year', 2012)),
            '2013' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('year', 2013)),
            '2014' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('year', 2014)),
			'2015' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('year', 2015)),	
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            ExportAction::make()
                ->label('Εξαγωγή σε Excel')
                ->exports([
                    ExcelExport::make()
                        ->fromTable()
                        ->except('has_relatives')
                        ->withFilename(fn ($resource) => $resource::getModelLabel() . '-' . date('Y-m-d'))
                        ->withWriterType(\Maatwebsite\Excel\Excel::XLSX)
                        ->withColumns([
                            //appending columns to table
                            Column::make('total_amount')->heading('Ποσό εντάλματος')->format(NumberFormat::FORMAT_CURRENCY_EUR),
                            Column::make('deductions')->heading('Κρατήσεις')->format(NumberFormat::FORMAT_CURRENCY_EUR),
                            Column::make('payment_amount')->heading('Ποσό δικαιούχου')->format(NumberFormat::FORMAT_CURRENCY_EUR),
                            Column::make('relative_files'),

                        ])
                ]),
        ];
    }


}
