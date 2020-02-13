<?php

namespace App\Imports;

use App\Stock;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StocksImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Stocks([
            //
        ]);
        if (!Stock::where('SKU', '=', $row['sku'])->exists()) {
            $stocks = new Stock([, 
                'SKU'                           => $row['sku'],
                'StockLevel'                    => $row['stocklevel'], 
                'ActionIndicator'               => $row['actionindicator'], 
                'StockAmount'                   => $row['stockamount'],
            ]);
            return $stocks;
        }else{
            
        }

}
