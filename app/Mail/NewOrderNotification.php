<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewOrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    /**
     * Create a new message instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject("New Order #{$this->order->order_number} Received")
                    ->view('emails.new_order_notification')
                    ->with([
                        'order' => $this->order,
                        'customerName' => $this->order->customer->first_name . ' ' . $this->order->customer->last_name,
                        'items' => $this->order->items,
                        'total' => $this->order->order_total,
                    ]);
    }
}