<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ApprovedAmbassadorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ambassador,$password;
    /**
     * Create a new message instance.
     */
    public function __construct($ambassador,$password)
    {
        $this->ambassador = $ambassador;
        $this->password = $password;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return$this->from(env('APP_MAIL'))
                   ->subject('Ambassador Approval')
                    ->view('mail.approved-ambassador')
                    ->with([
                        'ambassador' => $this->ambassador,
                        'password' => $this->password,
                    ]);


    }
}
