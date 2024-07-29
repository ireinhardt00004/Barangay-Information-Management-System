<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $content; 
    public $fullname;

    /**
     * Create a new message instance.
     *
     * @param  string  $content
     * @param  string  $fullname
     * @return void
     */
    public function __construct($content, $fullname)
    {
        $this->content = $content;
        $this->fullname = $fullname;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Reply to Your Report')
                    ->view('email.report_reply');
    }
}
