<?php

namespace App\Http\Controllers\apps;

use App\Models\User;
use App\Mail\SendEmail;
use Illuminate\Http\Request;
use App\Models\InvitationCode;
use App\Services\EmailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\EmailSendRequest;

class Email extends Controller
{
  protected $emailService;

  public function __construct(EmailService $emailService)
  {
    $this->emailService = $emailService;
  }

  public function index()
  {
    $users = User::all(); // Retrieve all users from the database
    $invitationCodes = InvitationCode::all();
    return view('content.apps.app-email', compact('users', 'invitationCodes'));
  }

  public function sendEmail(EmailSendRequest $request)
  {
    // Extract data from the validated request
    $recipients = $request->input('emailContacts');
    $cc = $request->input('email_cc', []);
    $bcc = $request->input('email_bcc', []);
    $subject = $request->input('email_subject');
    $message = $request->input('email_message');

    // Create the 'attachments' directory if it doesn't exist
    $attachmentDirectory = 'attachments';
    if (!\Storage::exists($attachmentDirectory)) {
      \Storage::makeDirectory($attachmentDirectory);
    }

    // Handle attachments
    $attachments = [];
    if ($request->hasFile('file-input')) {
      foreach ($request->file('file-input') as $file) {
        $path = $file->store($attachmentDirectory); // Store files in the 'attachments' directory
        $attachments[] = $path;
      }
    }

    // Send the email using the service class
    $this->emailService->sendEmail($recipients, $cc, $bcc, $subject, $message, $attachments);

    // Optionally, you can clear the uploaded attachments from storage if needed

    // Redirect back with a success message or handle errors
    return redirect()
      ->back()
      ->with('success', 'Email sent successfully.');
  }
}
