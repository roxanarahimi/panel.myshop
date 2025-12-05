<?php

namespace App\Filament\Resources\BaseProducts\Pages;

use App\Filament\Resources\BaseProducts\BaseProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBaseProducts extends ListRecords
{
    protected static string $resource = BaseProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
