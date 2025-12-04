<?php

namespace App\Filament\Resources\ProductInfos\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Text;
use File;
use Filament\Schemas\Schema;

class ProductInfoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                ImageEntry::make('images')->label('تصاویر')
                    ->disk('public')->visibility('public')
                    ->columnSpan(3)
                    ->size(200)
                    ->getStateUsing(fn($record) => $record->images)
                    // show 5 images, with +x more indicator
                     , // shows multiple images

                TextEntry::make('title')->label('عنوان')->columnStart(1)->columnSpan(1),
                TextEntry::make('category.title')->label('دسته بندی')->columnSpan(1),

                RepeatableEntry::make('products')->label('موجودی')
                    ->schema([
                        Grid::make(3)->schema([
                        TextEntry::make('color')->label('رنگ'),
                        TextEntry::make('size')->label('سایز'),
                        TextEntry::make('stock')->label('تعداد'),
                    ])
                    ])
                    ->columnStart(1)
//                IconEntry::make('visible')
//                    ->label('نمایش')
//                    ->boolean()
//                    ->trueColor('info')//->falseIcon('')
//                    ->falseColor('danger')->columnSpan(1),
//


            ]);
    }
}
