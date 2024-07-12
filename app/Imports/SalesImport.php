<?php

namespace App\Imports;

use App\Jobs\ProcessSalesChunk;
use App\Jobs\ProcessSalesInBatch;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * This class contains method related to Imports
 * 
 * @author Castro
 * 
 */
class SalesImport implements ToCollection, WithHeadingRow, WithChunkReading
{  

  /**
   * This method is used to chunk the rows in the file
   * 
   * @author Castro
   * 
   */
  public function collection(Collection $rows)
  {
    // Chunk the files
    $chunks = $rows->chunk($this->chunkSize());

    // Dispatch a job for each chunk for inserting values into DB.
    foreach ($chunks as $chunk) {
      ProcessSalesInBatch::dispatch($chunk->toArray());
    }
  }


  public function chunkSize(): int
  {
    return 5000; // Adjust the chunk size based for your performance testing, range of 1000-5000
  }
}
