<?php
namespace App\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GiftCardPurchasedMail extends Mailable
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
        return $this->from(env('APP_MAIL'))
            ->subject("You've received a gift card from ShadowsPhotoPrinting")
            ->view('mail.order.giftcard_purchased_mail')
            ->with('order', $this->order);

    }
}




?>
