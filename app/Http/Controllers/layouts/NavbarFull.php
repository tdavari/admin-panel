<?php

namespace App\Http\Controllers\layouts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NavbarFull extends Controller
{
  public function index()
  {
    return view('content.layouts-example.layouts-without-navbar');
  }
}
