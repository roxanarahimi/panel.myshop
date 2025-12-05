<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('کاربر')
                    ->getStateUsing(fn($record) => $record->user->name)->searchable(),
                TextColumn::make('code')->label('شماره')->searchable(),
                TextColumn::make('total_amount')->label('جمع فاکتور')->searchable(),
                TextColumn::make('total_off')->label('تخفیف'),
                TextColumn::make('amount')->label('مبلغ')->searchable(),
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
