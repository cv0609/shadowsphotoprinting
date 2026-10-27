<?php
namespace App\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftCardMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $fromSender = !empty($this->order['from']) ? ' from ' . $this->order['from'] : '';
        $isChristmas = !empty($this->order['is_christmas']);

        $subject = $isChristmas 
            ? "You've received a Christmas gift!" . $fromSender
            : "You've received an eGift Card" . $fromSender . " | Shadows Photo Printing";

        $mailFrom = config('mail.from.address') ?: env('APP_MAIL');
        $mailFromName = config('mail.from.name') ?: 'Shadows Photo Printing';

        return $this->from($mailFrom, $mailFromName)
            ->subject($subject)
            ->view('mail.order.giftcardmail')
            ->with('order', $this->order);
    }
}




?>
