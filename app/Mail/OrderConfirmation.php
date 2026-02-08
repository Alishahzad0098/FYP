<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\OrderItem;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $orderItems;

    /**
     * Create a new message instance.
     *
     * @param Order $order
     * @param array $orderItems
     */
    public function __construct(Order $order, array $orderItems)
    {
        $this->order = $order;
        $this->orderItems = $orderItems;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Order Confirmation - #' . $this->order->id)
            ->view('order_confirmation');

    }
}