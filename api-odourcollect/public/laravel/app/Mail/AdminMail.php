<?php

namespace App\Mail;

use App\Email;
use App\OdorEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $odor;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OdorEmail $odor)
    {
        $this->odor = $odor;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->odor->subject)->view('email.admin', ['odor' => $this->odor]);
    }
}
