<?php

namespace App\Filament\Resources\BaseProducts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BaseProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('images')
                    ->label('تصاویر')
                    ->image()
                    ->multiple()
                    ->disk('public')
                    ->directory('img/product')
                    ->visibility('public')
                    ->reorderable()
                    ->maxFiles(10)
                    ->imageEditor()
                    ->imageEditorEmptyFillColor('transparent')
                    ->imageCropAspectRatio('1:1'),

                TextInput::make('title')
                    ->label('عنوان')
                    ->columnStart(1)
                    ->required(),
                Select::make('category_id')
                    ->label('دسته بندی')
                    ->relationship('category', 'title')
                    ->required()
                    ->options(fn(callable $get) => \App\Models\Category::query()
//                        ->when(1, function ($query) {
//                            $query->where('type', 'contents')->where('visible', 1);
//                        })
                        ->pluck('title', 'id')
                    )
                    ->reactive() // important so options update when 'type' or 'active' changes
                    ->required()
                    ->searchable()
                    ->preload(),

                TextInput::make('price')
                    ->label('قیمت')
                    ->required(),
                TextInput::make('off')
                    ->label('تخفیف')
                    ->required(),
            ]);
    }
}
