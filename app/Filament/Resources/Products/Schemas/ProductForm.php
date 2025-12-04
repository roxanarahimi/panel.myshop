<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_info_id')
                    ->label('محصول پایه')
                    ->relationship('category', 'title')
                    ->required()
                    ->options(fn(callable $get) => \App\Models\Category::query()
                        ->pluck('title', 'id'))
                    ->reactive() // important so options update when 'type' or 'active' changes
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('color')
                    ->label('رنگ')
                    ->columnStart(1)
                    ->required(),
                TextInput::make('size')
                    ->label('سایز')
                    ->required(),
//                TextInput::make('dimensions')
//                    ->label('اندازه ها')
//                    ->required(),

                TextInput::make('price')
                    ->label('قیمت(اختیاری)'),
                TextInput::make('off')
                    ->label('تخفیف(اختیاری)'),
            ]);
    }
}
