<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'job_id',
        'status',
        'user_id',
        // Add other fillable properties as needed
    ];
}
