<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('تصاویر')
                    ->disk('public')
                    ->visibility('public')
                    ->size(50)
                    ->getStateUsing(fn($record) => $record->info->images)
                    ->limit(3)// show 3 images, with +x more indicator
                    ->rounded()
                    ->stacked() , // shows multiple images


                TextColumn::make('title')->label('عنوان')
                    ->getStateUsing(fn($record) => $record->info->title)->searchable(),
                TextColumn::make('color')->label('رنگ')->searchable(),
                TextColumn::make('size')->label('سایز')->searchable(),
                TextColumn::make('price')->label('قیمت')
                    ->getStateUsing(fn($record) => $record->price ? $record->price : $record->info->price),
                TextColumn::make('off')->label('تخفیف%')
                    ->getStateUsing(fn($record) => $record->off ? $record->off : $record->info->off),
                TextColumn::make('stock')->label('موجودی')->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
