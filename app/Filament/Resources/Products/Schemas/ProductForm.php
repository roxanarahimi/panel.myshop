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
                Select::make('base_product_id')
                    ->label('محصول پایه')
                    ->relationship('info', 'title')
                    ->required()
                    ->options(fn(callable $get) => \App\Models\BaseProduct::query()
                        ->pluck('title', 'id'))
                    ->reactive() // important so options update when 'type' or 'active' changes
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('color')
                    ->label('رنگ')
                    ->columnStart(1),
                TextInput::make('size')
                    ->label('سایز'),
//                TextInput::make('dimensions')
//                    ->label('اندازه ها')
//                    ->required(),

                TextInput::make('price')
                    ->label('قیمت(اختیاری)'),
                TextInput::make('off')
                    ->label('تخفیف(اختیاری)'),
                TextInput::make('stock')
                    ->label('موجودی'),
            ]);
    }
}
