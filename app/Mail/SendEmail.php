<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
  use Queueable, SerializesModels;

  public $emailContacts;
  public $emailCc;
  public $emailBcc;
  public $subject;
  public $emailMessage;
  public $attachments;

  /**
   * Create a new message instance.
   *
   * @param string $subject
   * @param string $emailContacts
   * @param string $emailMessage
   * @param string|null $emailCc
   * @param string|null $emailBcc
   * @param array|null $attachments
   */
  public function __construct(
    $emailContacts,
    $emailCc = null,
    $emailBcc = null,
    $subject,
    $emailMessage,
    $attachments = []
  ) {
    $this->emailContacts = $emailContacts;
    $this->emailCc = $emailCc;
    $this->emailBcc = $emailBcc;
    $this->subject = $subject;
    $this->emailMessage = $emailMessage;
    $this->attachments = $attachments;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(subject: 'Send Email');
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(view: 'emails.send-email');
  }

  /**
   * Get the attachments for the message.
   *
   * @return array<int, \Illuminate\Mail\Mailables\Attachment>
   */
  public function attachments(): array
  {
    return [];
  }

  public function build()
  {
    $messageContent = $this->emailMessage; // Assuming $this->emailMessage contains your HTML email content

    $message = $this->markdown('emails.send-email')
      ->subject($this->subject)
      ->to($this->emailContacts)
      ->with(['messageContent' => $messageContent])
      ->cc($this->emailCc)
      ->bcc($this->emailBcc);

    // Attach multiple files
    foreach ($this->attachments as $attachment) {
      $message->attachFromStorage($attachment);
    }

    return $message;
  }
}
