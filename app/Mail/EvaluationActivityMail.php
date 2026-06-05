<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EvaluationActivityMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $mailSubject,
        public string $approverName,
        public string $requestTitle,
        public string $requestorName,
        public string $url,
        public string $status
    ) {}



    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->mailSubject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.evaluation-activity',
            with: [
                'subject'       => $this->mailSubject,
                'approverName'  => $this->approverName,
                'requestTitle'  => $this->requestTitle,
                'requestorName' => $this->requestorName,
                'url'           => $this->url,
                'status'           => $this->status,
            ]
        );
    }

    // public function build()
    // {
    //     return $this->subject('Activity')
    //                 ->view('emails.evaluation-activity');
    // }
}
