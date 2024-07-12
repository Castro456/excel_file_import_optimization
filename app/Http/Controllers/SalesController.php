<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ProcessSalesImport;

/**
 * This class contains all the class related to file upload (Sales file)
 * 
 * @author Castro
 * 
 */
class SalesController extends Controller
{
    public function view()
    {
        return view('sales_import');
    }


    /**
     * This method is used to import the file and send to job for processing
     * 
     * @author Castro
     */
    public function import(Request $request)
    {
        // Validate the file format in correct and file is uploaded
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
        ]);

        $filePath = $request->file('file')->store('imports');

        // Dispatch the job
        ProcessSalesImport::dispatch($filePath);

        return redirect()->back()->with('success', 'Sales data imported successfully');
    }
}
