<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;


class ImportController extends Controller
{


	
    /**
    * Check if csv is there
    */
    public function check(){

    	$csv = ['products'];//, 'stocks'];
    	for ($i=0; $i < sizeof($csv); $i++) { 
    		
	    	try {
	    		$csvName = $csv[$i];
	            $handle = fopen($csvName . '.csv', "r");

	            $import = $this->import($handle, $csvName);
	            dd($import);
	            if ($import) {

	            	$myFile = '/path/to/my/file.txt';
					File::delete($myFile);
	            }else{

		            $message = "CSV: " . $csvName . " error -> " . $import;
		        	Log::error($message);
	            }

	    	} catch (Exception $e) {
	            
	            $message = "CSV: " . $csvName . " not found.";
	            $e->custom_message = $message;
	        	Log::error($message);
	            // report($e);
	            dd($e);
	    	}
    	}
    }



    /**
    * @return \Illuminate\Support\Collection
    */
    public function import($handle, $csvName)  {   

        $header = true;
        $headerArray = [];
        $row = 0;
        while ($csvLine = fgetcsv($handle, 1000, ",")) {
            if ($header) {
                $header = false;
                $headerArray = $csvLine;
            } else {

        		try {

        			$actionIndicatorId = array_search('ActionIndicator', $headerArray);
        			switch ($csvLine[$actionIndicatorId]) {

        				//Add Q
        				case 'A':

        					break;
        					
        				//Delete Q
        				case 'D':

        					break;
        					
        				//Update Q
        				case 'U':

        					break;
        				
        				default:
		        			Log::error('Unknown ActionIndicator: ' . $csvLine[$actionIndicatorId] . ' On Row: ' . $row);
        					break;
        			}

                    dd($csvLine, $headerArray);

                    // Character::create([
                    //     'name' => $csvLine[0] . ' ' . $csvLine[1],
                    //     'job' => $csvLine[2],
                    // ]);
            
		        } catch (Exception $e) {
		        	Log::error('Processing of Products csv: ');
		            report($e);
		            dd($e);
		            // return back();
		        }
            }
            $row += 1;
        }


        // Excel::import(new ProductsImport,request()->file('file'));
        // return back();
    }


}
