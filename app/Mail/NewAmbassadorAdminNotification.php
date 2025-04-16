<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewAmbassadorAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ambassador;

    /**
     * Create a new message instance.
     */
    public function __construct($ambassador)
    {
        $this->ambassador = $ambassador;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return$this->from(env('APP_MAIL'))
                   ->subject('New Ambassador Sign-Up')
                    ->view('mail.new-ambassador')
                    ->with('ambassador', $this->ambassador);
    }
}
