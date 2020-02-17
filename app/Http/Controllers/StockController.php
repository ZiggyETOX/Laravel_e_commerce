<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;
use App\Imports\StocksImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;
use App\Product;
use Exception;



class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store($row)
    {
        try {

            $initial_row = $row;
            $product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();
            unset($row['SKU']);

            $stock = $product->Stock()->get();
            if (sizeof($stock)==0) {
                $stock = new Stock();
            }else{
                $stock = $stock[0];
            }

            foreach ($row as $key => $value) {
             $stock[$key] = $value;
            }

            $stock->product_id = $product->id;
            $stock->save();
            return 1;

        } catch (Exception $e) {
            Log::error('Stock not saved. SKU: ' . $initial_row['SKU'] . ' Error: Most likely does not have a product to link to.');
            return 2;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Stock $stock)
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
            $product = Product::where('SKU', '=', $row['SKU'])->firstOrFail();
            unset($row['SKU']);

            $stock = $product->Stock()->get();
            if (sizeof($stock)==0) {
                $stock = new Stock();
            }else{
                $stock = $stock[0];
            }

            foreach ($row as $key => $value) {
             $stock[$key] = $value;
            }

            $stock->product_id = $product->id;
            $stock->save();
            return 1;

        } catch (Exception $e) {
            Log::error('Stock not saved. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
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
            unset($row['SKU']);

            $stock = $product->Stock()->get();
            if (sizeof($stock)!=0) {
                $stock = $stock[0];
                $stock->delete();
                return 1;
            }else{
                Log::error('Stock found. SKU: ' . $row['SKU']);
                return 2;
            }

        } catch (Exception $e) {
            Log::error('Stock not deleted. SKU: ' . $initial_row['SKU'] . ' Error: ' . $e);
            return 2;
        }
    }
}
