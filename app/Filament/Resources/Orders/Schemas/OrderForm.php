<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;


class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code')->label('کد'),
                TextEntry::make('payed_at')->label('تاریخ ثبت'),
                TextEntry::make('user.name')->label('گیرنده'),
                TextEntry::make('address.city.name')->label('مقصد'),
                Select::make('status')
                    ->label('وضعیت')
                    ->required()
                    ->options([
                        "payed" => 'ثبت شد',
                        "in progress" => 'آماده سازی',
                        "ready to send" => 'آماده ارسال',
                        "sent" => 'ارسال شد',
                        "delivered" => 'دریافت شد',
                        "canceled" => 'کنسل شد',
                    ])
                    ->reactive()
                    ->required()
                    ->preload()->columnStart(1),
                TextInput::make('post_tracking_number')
                    ->label('کد رهگیری پست'),
//                    ->columnStart(1)->html()->alignLeft()->columnSpanFull(),

            ]);
    }
}
