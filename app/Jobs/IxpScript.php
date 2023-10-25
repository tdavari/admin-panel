<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\JobStatus;

class IxpScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 240;
    public $jobId;

    /**
     * Create a new job instance.
     */
    
 

    public function __construct($jobId)
    {
        $this->jobId = $jobId;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        try {
            // Update job status to "running" when the job starts
            JobStatus::where('job_id', $this->jobId)->update(['status' => 'running']);
    
            // Logic to be executed when the job is processed
            // We're not capturing the output of exec since you mentioned it's not needed
            exec('/home/taha/dev/bin/python3 /home/taha/dev/genie-2-18-2023/ixp-vlan/ixp_datatables.py');
    
            // Update job status to "done" when the job is completed
            JobStatus::where('job_id', $this->jobId)->update(['status' => 'done']);
        } catch (\Exception $e) {
            // Update job status to "failed" if an exception occurs
            JobStatus::where('job_id', $this->jobId)->update(['status' => 'failed']);
            Log::error('IXP Updateall Job failed with message: ' . $e->getMessage());
        }
    }

    // public function getJobId()
    // {
    //     return $this->jobId;
    // }
    

}
