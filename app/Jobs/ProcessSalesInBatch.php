<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessSalesInBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $chunk;
    //SQL thrown's an error trying to insert a large number of rows in a single query, so break the insertion into smaller chucks.
    protected $batchSize = 500; //Can adjust

    /**
     * Create a new job instance.
     */
    public function __construct(array $chunk)
    {
        $this->chunk = $chunk;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $batches = array_chunk($this->chunk, $this->batchSize);

        foreach ($batches as $batch) {
            $data = [];
            
            foreach ($batch as $row) {
                try {
                    // If header contain spaces remove it.
                    $row = array_map('trim', $row);

                    $data[] = [
                        'region' => $row['region'] ?? null,
                        'country' => $row['country'] ?? null,
                        'item_type' => $row['item_type'] ?? null,
                        'sales_channel' => $row['sales_channel'] ?? null,
                        'order_priority' => $row['order_priority'] ?? null,
                        'order_date' => Carbon::parse($row['order_date'] ?? now()),
                        'order_id' => $row['order_id'] ?? null,
                        'ship_date' => Carbon::parse($row['ship_date'] ?? now()),
                        'units_sold' => $row['units_sold'] ?? null,
                        'unit_price' => $row['unit_price'] ?? null,
                        'unit_cost' => $row['unit_cost'] ?? null,
                        'total_revenue' => $row['total_revenue'] ?? null,
                        'total_cost' => $row['total_cost'] ?? null,
                        'total_profit' => $row['total_profit'] ?? null,
                    ];
                } catch (\Exception $e) {
                    // Throw an error on which row of processing the problem occurs. 
                    Log::error('Error processing row: ' . json_encode($row) . ' - Error: ' . $e->getMessage());
                }
            }

            if (!empty($data)) {
                // If all the rows are true then insert the data.
                DB::table('sales')->insert($data);
            }
        }
    }
}
