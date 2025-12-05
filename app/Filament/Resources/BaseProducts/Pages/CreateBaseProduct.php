<?php

namespace App\Filament\Resources\BaseProducts\Pages;

use App\Filament\Resources\BaseProducts\BaseProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBaseProduct extends CreateRecord
{
    protected static string $resource = BaseProductResource::class;
}
