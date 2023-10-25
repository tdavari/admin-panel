<?php

namespace App\Http\Controllers\Ixp;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VlanController extends Controller
{
  public function index()
  {
    $usernames = User::pluck('name');
    return view('ixp.vlan.vlan-request', compact('usernames'));
  }
}