<?php

namespace App\Http\Controllers\netbox;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class NetboxController extends Controller
{
  public function netboxUpdate(Request $request)
  {
    // Put the message in Redis
    Redis::publish('netbox-channel', 'Message sent to Python');

    return response()->json(['message' => 'Message sent to Python']);
  }

  public function setKeyValue()
  {
    // Set a key-value pair in Redis
    Redis::set('test_key', 'test_value');

    return response()->json(['message' => 'Key-value pair set in Redis']);
  }

  public function netboxStream()
  {
    // Define the stream name
    $streamName = 'mystream';

    // Define the message data as an array

    // Define the Redis command to send
    $command = [$streamName, '*', 'message', 'test'];

    // Send the custom Redis command
    Redis::command('XADD', $command);

    return response()->json(['message' => 'Message sent to Redis Stream']);
  }
}
