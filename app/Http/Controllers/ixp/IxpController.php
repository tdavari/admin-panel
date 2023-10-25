<?php

namespace App\Http\Controllers\ixp;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ixp;
use App\Jobs\IxpScript;
use App\Models\JobStatus;
use Auth;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class IxpController extends Controller
{
  public function table()
    {
        return view('ixp.table');
    }

  public function updateAll() {
   
        // Generate a jobId
        $jobId = Uuid::uuid4()->toString();

        // Dispatch the job to the queue with the jobId parameter
        IxpScript::dispatch($jobId)->onQueue('default');

        // Create a job status record to track the job and associate it with the user
        JobStatus::create([
            'job_id' => $jobId,
            'status' => 'queued',
            'user_id' => Auth::id()
        ]);

        // Redirect to the ixp.table page
        return redirect()->route('ixp.table')->with('success', 'Python script execution has been queued.');
    }

  public function index()
    {
        // Handle GET request to fetch data
        $data = Ixp::all();
        $response = ['data' => $data];
        return response()->json($response);
    }

    public function store(Request $request)
    {
        // Handle POST request to update data
        $requestData = $request->all();
        
        foreach ($requestData as $item) {
            Ixp::create($item);
        }
        return response()->json(['message' => 'Data updated successfully']);
    }

    public function destroy($id)
{
    // Find the record by ID and delete it
    $record = Ixp::find($id);

    if ($record) {
        $record->delete();
        return response()->json(['message' => 'Record deleted successfully']);
    } else {
        return response()->json(['message' => 'Record not found'], 404);
    }
}
public function deleteAll()
{
    // Truncate the table
    Ixp::truncate();

    return response()->json(['message' => 'All records deleted successfully']);
}
}
