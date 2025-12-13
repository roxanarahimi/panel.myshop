<?php

namespace App\Filament\Resources\Banners\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('image')
                    ->label('تصویر')
                    ->image()
                    ->disk('public')
                    ->directory('img/banner')
                    ->visibility('public')
                    ->imageEditor()
                    ->imageEditorEmptyFillColor('transparent')
                    ->imageCropAspectRatio('1500:660'),
                TextInput::make('link')
                    ->label('لینک')
                    ->columnStart(1)->columnSpanFull(),
                Select::make('visible')
                    ->label('نمایش')
                    ->options([
                        '0'=>'بله',
                        '1'=>'خیر'
                    ]),

            ]);
    }
}
