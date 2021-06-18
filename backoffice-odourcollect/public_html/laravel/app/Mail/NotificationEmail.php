<?php

namespace App\Mail;

use App\NotificationEmailModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $zone;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotificationEmailModel $zone)
    {
        $this->zone = $zone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->zone->subject)->view('emails.zone_notification', ['zone' => $this->zone]);
    }
}
