<?php

namespace App\Filament\Resources\BaseProducts\Pages;

use App\Filament\Resources\BaseProducts\BaseProductResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditBaseProduct extends EditRecord
{
    protected static string $resource = BaseProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
