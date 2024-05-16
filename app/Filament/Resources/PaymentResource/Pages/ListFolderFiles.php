<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;

class ListFolderFiles extends Page
{
	public $folderName;
    protected static string $resource = PaymentResource::class;

    public function getView(): string
    {
        return 'filament.resources.payment-resource.pages.list-folder-files';
    }

    public function mount($folderName)
    {
        $this->folderName = $folderName;
    }

    public function getFiles()
    {
		$filePaths = Storage::disk('public')->files('payments/' . $this->folderName);
		return array_map('basename', $filePaths);
    }

    public function getTitle(): string
    {
        return 'Τα αρχεία του φακέλου ' . $this->folderName . ' είναι:';
    }

}
