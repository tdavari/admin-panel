<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitationCode extends Model
{
  use HasFactory;

  protected $fillable = ['code', 'email', 'expires_at'];

  // Scope to check if the code is still valid
  public function scopeValid($query)
  {
    return $query->where('expires_at', '>', now());
  }
}
