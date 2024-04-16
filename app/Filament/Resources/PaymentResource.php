<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Beneficiaries;
use App\Models\Payment;
use App\Models\PaymentTypes;;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Εντάλματα';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ένταλμα')
                    ->description('Στοιχεία εντάλματος')
                    ->schema([
                        Select::make('payment_type_id')
                            ->label('Τύπος')
                            ->options(function (): array {
                                $cacheKey = 'payment_options';
                                return Cache::remember($cacheKey, now()->addMonths(2), function () {
                                    return PaymentTypes::all()->pluck('type', 'id')->all();
                                });
                            })->required()->columnSpanFull(),
                        TextInput::make('envelope_code')->label('Φάκελος')->required(),
                        TextInput::make('payment_code')->label('Κωδικός')->required(),
                        TextInput::make('year')->label('Έτος')->required(),
                        Select::make('beneficiary_id')
                            ->label("Δικαιούχος")
                            ->options(function (): array {
                                $cacheKey = 'beneficiary_options';
                                return Cache::remember($cacheKey, now()->addMonths(2), function () {
                                    return Beneficiaries::all()->pluck('beneficiary', 'id')->all();
                                });
                            })->required()->columnSpanFull(),
                        Textarea::make('description')->label('Περιγραφή')->required()->columnSpanFull()->rows(4),
                        TextInput::make('total_amount')->label('Σύνολο εντάλματος')->required(),
                        TextInput::make('deductions')->label('Κρατήσεις')->required(),
                        TextInput::make('payment_amount')->label('Ποσό δικαιούχου')->required(),
                    ])->columns(3)->columnSpan(2),
                Section::make('Αρχεία')
                    ->description('Επισυναπτόμενα')
                    ->schema([
                        TextInput::make('envelope_folder')->label('Κατάλογος')->required()->columnSpanFull(),
                        TextInput::make('filename')->label('Αρχείο')->required()->columnSpanFull(),
                        TextInput::make('relative_files')->label('Σχετικά αρχεία')->required()->columnSpanFull(),
                        TextInput::make('created_at')->label('Δημιουργήθηκε')->required()->columnSpanFull()->mask('9999/99/99'),
                        TextInput::make('updated_at')->label('Ενημερώθηκε')->required()->columnSpanFull()->mask('9999/99/99'),
                    ])->columnSpan(1)->footerActions(
                        [
                            Action::make('download all files')
                                ->label('Λήψη αρχείων')
                                ->action(function ($record) {
                                    $zip = new \ZipArchive();
                                    $zipFilePath = Storage::path('public\\payments\\' . 'pdfs-' . time() . '.zip');

                                    if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                                        $pdfPath = Storage::path('public\\payments\\' . $record->envelope_folder . '\\' . $record->filename);
                                        //add payment pdf to zip
                                        $zip->addFile($pdfPath, $record->filename);

                                        if ($record->has_relatives) {
                                            $relative_payments = array_map('trim', explode(',', $record->relative_files));
                                            foreach ($relative_payments as $relative_payment) {
                                                $payment = DB::table('payments')->where('file_code', $relative_payment)->first();
                                                $pdfPath = Storage::path('public\\payments\\' . $payment->envelope_folder . '\\' . $payment->filename);
                                                if (file_exists($pdfPath)) {
                                                    //add relatives payment to zip if exists
                                                    $zip->addFile($pdfPath, $payment->filename);
                                                }
                                            }
                                        }

                                        $zip->close();
                                    }
                                    return response()->download($zipFilePath)->deleteFileAfterSend(true);
                                }),



                        ]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (Model $record) => match ($record->has_relatives) {
                0 => 'border-s-2 border-orange-600 dark:border-orange-300',
                1 => 'border-s-2 border-green-600 dark:border-green-300',
                default => null,
            })
            ->striped()
            ->columns([
                TextColumn::make('envelope_code')->label('Φάκελος')->searchable(),
                TextColumn::make('payment_code')->label('Κωδικός')->searchable(),
                TextColumn::make('year')->label('Έτος'),
                TextColumn::make('paymentType.type')->label('Τύπος')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ΕΝΤΠε', 'ΕΝΤΠ', 'ΕΝΤΠΠ' => 'success',
                        'ΕΝΤ-ΚΡΑΤ' => 'warning',
                        'ΑΚΥΡ-ΕΝΤ' => 'danger',
                        'ΜΙΣΘ-ΜΟΝ', 'ΜΙΣΘ-ΜΟΝε' => 'info',
                        default => 'grey'
                    }),
                TextColumn::make('beneficiary.beneficiary')->label('Δικαιούχος')->wrap(),
                TextColumn::make('description')->label('Περιγραφή')->wrap()->searchable(),
                IconColumn::make('has_relatives')->label('Αρχεία')->wrap()->boolean()->size(IconColumn\IconColumnSize::Medium),
            ])
            ->filters([
                SelectFilter::make('has_relatives')
                    ->label('Έχει άλλα αρχεία')
                    ->options([
                        1 => 'Ναι',
                        0 => 'Όχι',
                    ])
                    ->attribute('has_relatives'),
                SelectFilter::make('paymentType')
                    ->label('Τύπος')
                    ->relationship('paymentType', 'type'),
                SelectFilter::make('beneficiary')
                    ->label('Δικαιούχος')
                    ->relationship('beneficiary', 'beneficiary')
                    ->searchable()
                    ->preload(),
            ], layout: FiltersLayout::AboveContent)
            ->actions([
                ViewAction::make()->label('')->iconSize('lg'),
                \Filament\Tables\Actions\Action::make('download_payment_pdf')
                    ->label('')
                    ->url(function ($record) {
                        return self::goTo($record->envelope_folder, $record->filename, $record->filename, 'Λήψη αρχείου');
                    })
                    ->icon('heroicon-o-document-arrow-down')
                    ->iconSize('lg')
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                BulkAction::make('downloadPdfs')
                    ->label('Λήψη Ενταλμάτων')
                    ->action(function (Collection $records) {
                        $zip = new \ZipArchive();
                        $zipFileName = 'pdfs-' . time() . '.zip';
                        $zipFilePath = Storage::path('public\\payments\\' . $zipFileName );

                        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
                            foreach ($records as $record) {
                                $pdfPath = Storage::path('public\\payments\\' . $record->envelope_folder . '/' . $record->filename);
                                if (file_exists($pdfPath)) {
                                    $zip->addFile($pdfPath, $record->filename);
                                }
                            }
                            $zip->close();
                        }
                        return response()->download($zipFilePath)->deleteFileAfterSend(true);
                    })
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }

    protected static function goTo(string $envelope, string $filename, ?string $tooltip = null): string
    {
        return Storage::url('payments/' . $envelope . '/' . $filename);
    }

}
