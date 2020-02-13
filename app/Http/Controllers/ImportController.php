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
	        	Log::error($message);
	            report($e);
	            dd('check()', $e);
	    	}
    	}
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
        			// dd($row);
        			$importJob = new ProcessImport($row);
        			dispatch($importJob);

		        } catch (Exception $e) {
		        	dd($e);
		        	Log::error('Job did not dispatch error: ' . $e . ' Row: ' . $rowcount);
		        }
            }
            $rowcount++;
        }
		return 'Import in queue.';

        // Excel::import(new ProductsImport,request()->file('file'));
        // return back();
    }




    // /**
    // * Add import action 
    // */
    // public function importAdd($row)  {

    // 	if ($row['ImportType'] == "Product") {

    // 		unset($row['ImportType']);
    // 		try {

		  //   	$product = Product::firstOrNew(['SKU' => $row['SKU']]);
		  //   	foreach ($row as $key => $value) {
		  //   		$product[$key] = $value;
		  //   	}
		  //   	$product->save();
		  //   	return 1;

    // 		} catch (Exception $e) {
	   //      	Log::error('Product not saved. SKU: ' . $row['SKU'] . ' Error: ' . $e);
	   //      	return 2;
    // 		}

    // 	}elseif ($row['ImportType'] == "Stock") {

    // 		unset($row['ImportType']);
    // 		try {

		  //   	// $stock = Stock::firstOrNew(['SKU' => $row['SKU']]);
		  //   	// foreach ($row as $key => $value) {
		  //   	// 	$stock[$key] = $value;
		  //   	// }

		  //   	$product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();
		  //   	$stock = $product->stock;
		  //   	dd($stock);
		  //   	$stock->save();
		  //   	return 1;

    // 		} catch (Exception $e) {
    // 			dd($e);
	   //      	Log::error('Product not saved. SKU: ' . $row['SKU'] . ' Error: ' . $e);
	   //      	return 2;
    // 		}

    // 	}
    // }


    // /**
    // * Delete import action 
    // */
    // public function importDelete($row)  {

    // 	if ($row['ImportType'] == "Product") {

    // 		unset($row['ImportType']);
    // 		try {

		  //   	$product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();
		  //   	$product->delete();

		  //   	return 1;

    // 		} catch (Exception $e) {
	   //      	Log::error('Product to be deleted not found. SKU: ' . $row['SKU'] . ' Error: ' . $e);
	   //      	return 2;
    // 		}
    // 	}
    // }
    // /**
    // * Update import action 
    // */
    // public function importUpdate($row)  {

    // 	if ($row['ImportType'] == "Product") {

    // 		unset($row['ImportType']);
    // 		try {

		  //   	$product = Product::firstOrNew(['SKU' => $row['SKU']]);
		  //   	foreach ($row as $key => $value) {
		  //   		$product[$key] = $value;
		  //   	}

		  //   	$product->save();
		  //   	return 1;

    // 		} catch (Exception $e) {
	   //      	Log::error('Product not saved. SKU: ' . $row['SKU'] . ' Error: ' . $e);
	   //      	return 2;
    // 		}

    // 	}elseif ($row['ImportType'] == "Stock") {

    // 		unset($row['ImportType']);

    // 	}

    // }

}
