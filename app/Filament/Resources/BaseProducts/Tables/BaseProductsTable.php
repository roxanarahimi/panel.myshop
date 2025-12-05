<?php

namespace App\Filament\Resources\BaseProducts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BaseProductsTable
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
                    ->getStateUsing(fn($record) => $record->images)
                    ->limit(3)
                    ->rounded()
                    ->stacked() , // shows multiple images


                TextColumn::make('title')->label('عنوان')->searchable() ->sortable(),
                TextColumn::make('category.title') ->label('دسته بندی'),

                TextColumn::make('price')->label('قیمت')->searchable(),
                TextColumn::make('off')->label('تخفیف'),
                TextColumn::make('stock')->label('موجودی')
                    ->getStateUsing(fn($record) => $record->products->sum('stock')),

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
