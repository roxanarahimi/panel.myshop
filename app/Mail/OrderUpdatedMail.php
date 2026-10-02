<?php

namespace App\Mail;

use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public OrderResource $order
    ) {}

    public function build()
    {
        return $this
            ->from('noreply@rxshop.ir', 'RXShop')
            ->subject('بروزرسانی سفارش')
            ->view('emails.order-updated');
    }
}
