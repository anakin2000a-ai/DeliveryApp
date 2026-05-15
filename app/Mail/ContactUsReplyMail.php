<?php
namespace App\Mail;

use App\Models\ContactUs;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ContactUs $contactUs,
        public string $replyMessage
    ) {}

    public function build(): self
    {
        return $this
            ->subject('Reply: ' . $this->contactUs->subject)
            ->markdown('emails.contact-us.reply');
    }
}