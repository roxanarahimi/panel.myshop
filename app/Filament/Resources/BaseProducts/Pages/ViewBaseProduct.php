<?php

namespace App\Filament\Resources\BaseProducts\Pages;

use App\Filament\Resources\BaseProducts\BaseProductResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewBaseProduct extends ViewRecord
{
    protected static string $resource = BaseProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
