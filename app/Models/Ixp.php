<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ixp extends Model
{
    use HasFactory;

    protected $table = 'ixp'; // Set the table name if it's different

    // Define the fillable fields if needed
    protected $fillable = [
        'switch', 'vlan', 'name', 'vni', 'intf', 'desc', 'learn_mac', 'count'
    ];
    protected $casts = [ 'learn_mac'=> 'array'];
}
