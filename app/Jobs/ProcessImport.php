<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Exception;


use app\Http\Controllers\ImportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;

class ProcessImport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $row;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($row)
    {
        $this->row = $row;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $initial_row = $this->row;
        try {
            
            if ($this->row['ImportType'] == "Product") {
                $importController = new ProductController();
            }
            else{
                $importController = new StockController();
            }

            unset($this->row['ImportType']);
            $Process = 0;
            switch ($this->row['ActionIndicator']) {
                case 'A':
                    unset($this->row['ActionIndicator']);
                    unset($this->row['row_number']);
                    $Process = $importController->store($this->row);
                    break;
                case 'D':
                    unset($this->row['ActionIndicator']);
                    unset($this->row['row_number']);
                    $Process = $importController->destroy($this->row);
                    break;
                case 'U':
                    unset($this->row['ActionIndicator']);
                    unset($this->row['row_number']);
                    $Process = $importController->update($this->row);
                    break;
                
                default:
                    # code...
                    break;
            }
            if ($Process == 0 || $Process == 2 ) {

                // dd('Jobs/importer', $Process);
                // Log::error('ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU']);             
                // Log::notice('UN-successfully executed Row : ' . $initial_row['row_number']);              
                Log::channel('importLog')->info('UN-successfully executed Row : ' . $initial_row['row_number']);      
            }elseif($Process == 1) {       
                Log::channel('importLog')->info($initial_row['ImportType'] . ' successfully executed Row : ' . $initial_row['row_number']);
                // Log::notice($initial_row['ImportType'] . ' successfully executed Row : ' . $initial_row['row_number']);
            }
        } catch (Exception $e) {
            // Log::error('ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU']);         
                // Log::notice('UN-successfully executed : ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU'] . ' row Number: ' . $initial_row['row_number'] . ' Error: ' . $e);            
                Log::channel('importLog')->info('UN-successfully executed : ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU'] . ' row Number: ' . $initial_row['row_number'] . ' Error: ' . $e);      
        }
    }
}
