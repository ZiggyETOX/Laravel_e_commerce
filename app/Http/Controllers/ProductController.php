<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

use App\Stock;
use Darryldecode\Cart\Cart;

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
        $user = \Auth::user();
        $CartItems = \Cart::session($user->id)->getContent();

        $return['cartItems'] = $CartItems;
        $return['products'] = $products;
        return view('/products/index', $return);
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
            $process = ['1','Proudct Added'];
            return $process;

        } catch (Exception $e) {
            Log::error('Product not saved. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            $process = ['2', 'Product Not Added: check logs'];
            return $process;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {
            $stock = $product->Stock()->firstOrFail();
            $stockQuantity = $stock->StockAmount;
        } catch (Exception $e) {
            $stockQuantity = 0;
        }
        $Items = \Cart::getContent();
        // dd($Items);
        $user = \Auth::user();
        $CartItems = \Cart::session($user->id)->getContent();

        $return['Items'] = $Items;
        $return['cartItems'] = $CartItems;
        $return['stockQuantity'] = $stockQuantity;
        $return['product'] = $product;
        // dd($return);
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
            $process = ['1','Proudct Updated'];
            return $process;

        } catch (Exception $e) {
            Log::error('Product not saved. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            $process = ['2', 'Product Not updated: check logs'];
            return $process;
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
            $msg = 'Proudct Deleted';
            if (sizeof($stock)==0) {
                $msg = $msg . ' No Stock found to delete';
                // no stock for this product.
            }else{
                $stocks = $stock;
                foreach ($stocks as $stock) {
                    $stock->delete();
                    $msg = $msg . ' Stock also Deleted';
                }
            }

            // Deletes product.
            $product->delete(); 
            $process = ['1', $msg];
            return $process;

        } catch (Exception $e) {
            Log::error('Product to be deleted not found. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            $process = ['2', 'Product not found to delete'];
            return $process;
        }
    }

}
