<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class OrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')->label('شماره سفارش')->columnStart(1)->columnSpan(1),
                TextEntry::make('user.name')->label('کابر')->columnStart(1)->columnSpan(1),
                TextEntry::make('user.mobile')->label('شماره موبایل')->columnSpan(1),
                TextEntry::make('address.postal_code')->label('کد پستی')->columnStart(1)->columnSpan(1),
                TextEntry::make('address.address')->label('ادرس'),


                RepeatableEntry::make('orderItems')->label('محصولات')
                    ->schema([
                        Grid::make(8)->schema([
                            ImageEntry::make('images')->label('تصویر')
                                ->disk('public')->visibility('public')
                                ->columnSpan(1)
                                ->size(50)
                                ->getStateUsing(fn($record) => $record->product->info->images)->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => $record->product->info->title)->label('عنوان')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => $record->product->color)->label('رنگ')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => $record->product->size)->label('سایز')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => $record->quantity)->label('تعداد')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => $record->product->price ? $record->product->price : $record->product->info->price)->label('قیمت واحد')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => ($record->product->price ? $record->product->price : $record->product->info->price) * ($record->product->off ? $record->product->off : $record->product->info->off) / 100)->label('تخفیف واحد')->columnSpan(1),
                            TextEntry::make('product')->getStateUsing(fn($record) => (
                                ($record->product->price ? $record->product->price : $record->product->info->price)
                                    - (
                                        ($record->product->price ? $record->product->price : $record->product->info->price)
                                    * (($record->product->off ? $record->product->off : $record->product->info->off) / 100)

                                ) ) * $record->quantity)->label('مبلغ')->columnSpan(1),
                        ])
                    ])
                    ->columnSpan(3),

                TextEntry::make('total_amount')->label('جمع کل'),
                TextEntry::make('total_off')->label('تخفیف کل'),
                TextEntry::make('amount')->label('مبلغ نهایی'),


            ]);
    }
}
