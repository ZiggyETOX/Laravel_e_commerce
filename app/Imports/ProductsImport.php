<?php

namespace App\Imports;

use App\Product;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {    

        if (!Product::where('SKU', '=', $row['sku'])->exists()) {
            $products = new Product([
                'ProductName'                   => $row['productname'],
                'ProductShortDescription'       => $row['productshortdescription'], 
                'CSProductCategory'             => $row['csproductcategory'], 
                'CSProductSubCategory'          => $row['csproductsubcategory'], 
                'TopLevel'                      => $row['toplevel'], 
                'SKU'                           => $row['sku'],
                'NewDate'                       => $row['newdate'],
                'Promotion'                     => $row['promotion'],
                'SAPrice'                       => $row['sa_price'],
                'BotswanaPrice'                 => $row['botswana_price'],
                'NamibiaPrice'                  => $row['namibia_price'],
                'ActionIndicator'               => $row['actionindicator'], 
            ]);
            return $products;
        }else{
            
        }

    }
}
