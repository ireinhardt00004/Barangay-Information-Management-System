<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EventNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $event;

    public function __construct($event)
    {
        $this->event = $event;
    }

    public function build()
    {
        return $this->view('email.event_notification')
                    ->subject('New Event Notification')
                    ->with([
                        'title' => $this->event['title'],
                        'description' => $this->event['description'],
                        'type' => $this->event['type'],
                        'start_datetime' => $this->event['start_datetime'],
                        'end_datetime' => $this->event['end_datetime'],
                    ]);
    }
}
