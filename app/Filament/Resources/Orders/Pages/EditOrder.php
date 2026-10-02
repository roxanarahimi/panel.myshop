<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Http\Resources\OrderItemResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderUpdatedMail;
class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;


    public function __construct(
         public \App\Http\Resources\OrderResource $order
    ) {}

    protected function afterSave(): void
    {
        if ($this->record->wasChanged('status') && ($this->record->status === 'sent')) {
           if ($this->record->user->email_verified_at !== null) {
               Mail::to($this->record->user->email)
                   ->send(new OrderUpdatedMail(new \App\Http\Resources\OrderResource($this->record)));
           }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
