<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Imports\SalesImport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ProcessSalesImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    /**
     * Create a new job instance.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        ini_set('memory_limit', '2048M'); //set the memory limit of 2gb for safer side while handling 1 million records

        try {
            $startTime = microtime(true); //Process start time
            $startMemory = memory_get_usage(); //Memory before the start time

            Excel::import(new SalesImport, $this->filePath);
            
            $endTime = microtime(true);
            $endMemory = memory_get_usage();
            
            Log::info("Import completed in:". ($endTime - $startTime) ."seconds.");
            Log::info("Memory usage: " . ($endMemory - $startMemory) . " bytes.");
        } 
        catch (\Exception $e) {
            Log::error('Sales file upload failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
