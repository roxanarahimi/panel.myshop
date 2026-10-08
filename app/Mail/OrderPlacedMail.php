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

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public $order) {
    }

    public function build()
    {
        return $this
            ->from('noreply@rxshop.ir', 'RXShop')
            ->subject('ثبت سفارش')
            ->view('emails.order-placed');
    }
    /**
     * Get the message envelope.
     */
//    public function envelope(): Envelope
//    {
//        return new Envelope(
//            subject: 'Order Placed Mail',
//        );
//    }

    /**
     * Get the message content definition.
     */
//    public function content(): Content
//    {
//        return new Content(
//            view: 'view.name',
//        );
//    }
//
//    /**
//     * Get the attachments for the message.
//     *
//     * @return array<int, Attachment>
//     */
//    public function attachments(): array
//    {
//        return [];
//    }
}
