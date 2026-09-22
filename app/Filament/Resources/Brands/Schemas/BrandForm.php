<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('تصویر')
                    ->image()
                    ->disk('public')
                    ->directory('img/category')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorEmptyFillColor('transparent')
//                    ->circleCropper()
                    ->imageCropAspectRatio('1:1'),
                TextInput::make('name')
                    ->label('نام')
                    ->required()->columnStart(1),
                TextInput::make('made_in')
                    ->label('ساخت کشور')
                    ->required(),
            ]);
    }
}
