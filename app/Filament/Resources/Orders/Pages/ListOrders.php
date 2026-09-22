<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Category;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
    public function getTabs(): array
    {
        $tabs = [];

        // Default "All" tab
        $statuses = [
//              ["value"=>'cart',"title"=>'سبد خرید'],
              ["value"=>'payed',"title"=>'ثبت شد'],
              ["value"=>'in progress',"title"=>'آماده سازی'],
              ["value"=>'ready to send',"title"=>'آماده ارسال'],
              ["value"=>'sent',"title"=>'ارسال شد'],
              ["value"=>'delivered',"title"=>'دریافت شد'],
              ["value"=>'canceled',"title"=>'کنسل شد'],
        ];
        foreach ($statuses as $status) {
            $tabs["status-{$status['value']}"] = Tab::make($status['title'])
                ->modifyQueryUsing(fn (Builder $query) =>
                $query->where('type', 'order')
                    ->where('status', $status['value'])
                );
        }

        return $tabs;
    }
}
