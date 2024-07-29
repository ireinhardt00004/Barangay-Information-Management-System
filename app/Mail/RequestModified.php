<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestModified extends Mailable
{
    use Queueable, SerializesModels;

    public $status;
    public $requestName;
    public $reason;

    public function __construct($status, $requestName, $reason = null)
    {
        $this->status = $status;
        $this->requestName = $requestName;
        $this->reason = $reason;
    }

    public function build()
    {
    return $this->view('email.request_modified')
                    ->with([
                        'status' => $this->status,
                        'requestName' => $this->requestName,
                        'reason' => $this->reason,
                    ]);
    }
}
