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
        //
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

            $product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();
            $product->delete();

            return 1;

        } catch (Exception $e) {
            Log::error('Product to be deleted not found. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            return 2;
        }
    }

}
