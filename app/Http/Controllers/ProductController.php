<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use App\Stock;

use Exception;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $products = \App\Product::all();
        $return['products'] = $products;
        return view('/products/index', $return);
        // dd($product);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store($row)
    {   
        try {
            $initial_row = $row;

            $product = Product::firstOrNew(['SKU' => $row['SKU']]);
            foreach ($row as $key => $value) {
                $product[$key] = $value;
            }
            $product->save();
            return 1;

        } catch (Exception $e) {
            Log::error('Product not saved. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            return 2;
        }
        // dd($row);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $products = \App\Product::all();
        $return['product'] = $product;
        return view('/products/show', $return);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update($row)
    {
        try {
            $initial_row = $row;

            $product = Product::firstOrNew(['SKU' => $row['SKU']]);
            foreach ($row as $key => $value) {
                $product[$key] = $value;
            }

            $product->save();
            return 1;

        } catch (Exception $e) {
            Log::error('Product not saved. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            return 2;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     */
    public function destroy($row)
    {
        try {
            $initial_row = $row;

            //check if product exists if not it will fail
            $product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();

            //check if the product has stock. If it does delete it
            $stock = $product->Stock()->get();
            if (sizeof($stock)==0) {
                // no stock for this product.
            }else{
                $stocks = $stock;
                foreach ($stocks as $stock) {
                    $stock->delete();
                }
            }
            // Deletes product.
            $product->delete(); 

            return 1;

        } catch (Exception $e) {
            Log::error('Product to be deleted not found. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            return 2;
        }
    }

}
