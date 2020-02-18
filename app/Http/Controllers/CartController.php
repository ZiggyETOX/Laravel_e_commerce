<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Product;
use Melihovv\ShoppingCart\ShoppingCart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        dd('awe');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $product = Product::find($request->id)->firstOrFail();
            $stock = $product->Stock()->first();

            if ($stock->StockAmount < $request->Quantity) {

                return redirect('/products/' . $request->id)->with('error', 'Quantity selected is more then we have in stock!');
            }else{  

                // $cartItem = Cart::add($id, $name, $price, $quantity);
                $cart = new ShoppingCart();
                $cartItem = $cart->add($request->id, $product->ProductName, $product->SAPrice, $request->Quantity);
                dd($cartItem);
                // $user = \Auth::user();
                // Cart::store($user->id);
                // Cart::instance('cart')->store($user->id);
                // Cart::instance('wishlist')->store($user->id);

            }
        } catch (Exception $e) {
            
        }

        //check if the product has stock. If it does delete it
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
