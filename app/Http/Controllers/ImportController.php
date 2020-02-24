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
	            $handle = fopen('imports/' . $csvName . '.csv', "r");

	            $import = $this->import($handle, $csvName);
	            if ($import) {

					// $myFile = '/path/to/my/file.txt';
					// File::delete($myFile);
	            }else{

		        	Log::error("CSV: " . $csvName . " error -> " . $import);
	            }

	    	} catch (Exception $e) {
	    		Log::channel('check_for_csv')->info("CSV: " . $csvName . '.csv Not found.');
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

        // dd($handle);
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
        			
                    // $this->Job($row);
        			$importJob = new ProcessImport($row);
        			dispatch($importJob)->onQueue('Import')->onConnection('rabbitmq');


		        } catch (Exception $e) {
		        	dd($e);
		        	Log::error('Job did not dispatch error: ' . $e . ' Row: ' . $rowcount);
		        }
            }
            $rowcount++;
        }
		return TRUE;
    }

}
