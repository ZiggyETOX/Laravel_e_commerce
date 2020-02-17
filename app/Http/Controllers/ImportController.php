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

	            $import = $this->import($handle, $csvName);
	            // dd($import);
	            if ($import) {

					// $myFile = '/path/to/my/file.txt';
					// File::delete($myFile);
	            }else{

		            $message = "CSV: " . $csvName . " error -> " . $import;
		        	Log::error($message);
	            }

	    	} catch (Exception $e) {
	            
	            $message = "CSV: " . $csvName . " not found.";
	            $e->custom_message = $message;
	        	Log::error($e);
	    	}
    	}
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

        			$importJob = new ProcessImport($row);
        			dispatch($importJob);


		        } catch (Exception $e) {
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
