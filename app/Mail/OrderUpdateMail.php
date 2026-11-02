<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;

/**
 * Order tracking email (to the customer or to staff). Queued, so it is sent
 * by the queue worker when QUEUE_CONNECTION is database/redis and straight
 * away when it is sync. Data is plain values so the job serialises safely.
 */
class OrderUpdateMail extends Mailable implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $mailData;

    public function __construct($subject, array $data)
    {
        $this->subject = $subject;
        $this->mailData = $data;
    }

    public function build()
    {
        return $this->view('mail.order_tracking_update', $this->mailData);
    }
}
