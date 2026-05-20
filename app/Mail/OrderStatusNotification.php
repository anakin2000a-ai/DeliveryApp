<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $status;
    public ?string $note;

    public function __construct(Order $order, string $status, ?string $note = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->note = $note;
    }

    public function build(): self
    {
        return $this->subject("Your order #{$this->order->order_number} is now {$this->status}")
                    ->view('emails.order_status_notification')
                    ->with([
                        'order' => $this->order,
                        'status' => $this->status,
                        'note' => $this->note,
                    ]);
    }
}