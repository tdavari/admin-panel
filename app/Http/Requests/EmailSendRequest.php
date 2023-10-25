<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailSendRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return auth()->check();
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
   */
  public function rules()
  {
    return [
      'emailContacts' => 'required|array|min:1', // Assuming you want at least one email contact.
      'emailContacts.*' => 'required|email', // Use * to indicate an array of emails
      'email_cc.*' => 'nullable|email',
      'email_bcc.*' => 'nullable|email',
      'email_subject' => 'required|string',
      'email_message' => 'required|string',
      'file-input.*' => 'nullable|file|mimes:pdf,doc,docx,txt,zip,rar,jpg,jpeg,png,gif|max:20000',
    ];
  }
}
