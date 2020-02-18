<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use App\Jobs\ProcessImport;
use Exception;


class ImportController extends Controller
{


	
    /**
    * Check if csv is there
    */
    public function check(){

    	$csv = [
    		'Product',
    		'Stock'
    	];
    	for ($i=0; $i < sizeof($csv); $i++) { 
    		
	    	try {
	    		$csvName = $csv[$i];
	            $handle = fopen($csvName . '.csv', "r");

	            dd('do not import');
	            $import = $this->import($handle, $csvName);
	            if ($import) {

					// $myFile = '/path/to/my/file.txt';
					// File::delete($myFile);
	            }else{

		        	Log::error("CSV: " . $csvName . " error -> " . $import);
	            }

	    	} catch (Exception $e) {
	    		Log::channel('check_for_csv')->info("CSV: " . $csvName . " not found." . $e);
	    	}
    	}
    	// Queue:work();
    	dd('Completed.');
    }


    /**
    * @return \Illuminate\Support\Collection
    */
    public function import($handle, $csvName)  {   

        $header = true;
        $headerArray = [];
        $rowcount = 1;
        while ($csvLine = fgetcsv($handle, 1000, ",")) {
            if ($header) {
                $header = false;
                foreach ($csvLine as $key => $value) {
                	array_push($headerArray, str_replace(" ", "", $value));
                }
            } else {
        		try {

        			$row = array_combine($headerArray, $csvLine);
        			$row['ImportType'] = $csvName;
        			$row['row_number'] = $rowcount;

        			// ProcessImport::dispatch($row)
        			// 	->delay(now()->addMinutes(1));
        			// ProcessImport::dispatch($row)->onQueue('rabbitmq');
        			// ProcessImport::dispatch($row)->onConnection('rabbitmq');
        			
        			$importJob = new ProcessImport($row);
        			dispatch($importJob);
        			// ProcessImport::dispatch($row);
        			// 	->delay(now()->addMinutes(1));


		        } catch (Exception $e) {
		        	dd($e);
		        	Log::error('Job did not dispatch error: ' . $e . ' Row: ' . $rowcount);
		        }
            }
            $rowcount++;
        }
        // dd('all jobs dispatched.');
		return TRUE;

        // Excel::import(new ProductsImport,request()->file('file'));
        // return back();
    }
}
