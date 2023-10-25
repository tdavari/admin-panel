<?php

namespace App\Services;

use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

class EmailService
{
  public function sendEmail($recipients, $cc, $bcc, $subject, $message, $attachments)
  {
    try {
      $mailer = new SendEmail($recipients, $cc, $bcc, $subject, $message, $attachments);
      // dd($mailer);
      Mail::send($mailer);

      return true; // Email sent successfully.
    } catch (\Exception $e) {
      dd($e);
      // Log or handle the exception.
      return false; // Failed to send email.
    }
  }
}
