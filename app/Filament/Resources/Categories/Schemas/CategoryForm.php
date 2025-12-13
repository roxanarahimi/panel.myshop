<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CategoryForm
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
                TextInput::make('title')
                    ->label('عنوان')
                    ->columnStart(1)
                    ->required(),

            ]);
    }
}
