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
                    $Process = $importController->store($this->row);
                    break;
                case 'D':
                    unset($this->row['ActionIndicator']);
                    $Process = $importController->destroy($this->row);
                    break;
                case 'U':
                    unset($this->row['ActionIndicator']);
                    $Process = $importController->update($this->row);
                    break;
                
                default:
                    # code...
                    break;
            }
            if ($Process == 0) {

                dd('Jobs/importer', $Process);
                Log::error('ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU']);            
            }elseif($Process == 1) {
                Log::notice('Row successfully run.');       
            }
        } catch (Exception $e) {
            Log::error('ActionIndicator Unknown: ' . $initial_row['ActionIndicator'] . ' SKU: ' . $initial_row['SKU']); 
            dd($e);
        }
    }
}
