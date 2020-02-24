<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use App\Product;
use App\DatabaseStorageModel;

use Darryldecode\Cart\Cart;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $CartItems = \Cart::session($user->id)->getContent();

        $return['cartItems'] = $CartItems;
        // dd($return['cartItems']['1_1']->associatedModel->id);
        return view('/cart/show', $return);
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
        if ($request->Quantity == 0) {
            return redirect('/products/' . $request->id)->with('error', 'Quantity selected is 0');
        }
        try {
            $product = Product::find($request->id);
            $stock = $product->Stock()->first();


            if ($stock->StockAmount < $request->Quantity) {

                return redirect('/products/' . $request->id)->with('error', 'Quantity selected is more then we have in stock!');
            }else{  
                $user = \Auth::user();
                $rowId = $user->id . '_' . $product->id;
                $cartItem = array(
                    'id' => $rowId,
                    'name' => $product->ProductName,
                    'price' => $product->SAPrice,
                    'quantity' => $request->Quantity,
                    'attributes' => array('SKU' => $product->SKU),//, 'product_id' => $product->id),
                    'associatedModel' => $product
                );
            // dd($cartItem,$product );
                // $CartAdd = \Cart::session($user->id)->add($cartItem);
                $DatabaseStorageModel =  new DatabaseStorageModel();

                $DatabaseStorageModel->setCartDataAttribute($cartItem, $rowId);
                // $DatabaseStorageModel->id = $rowId;

                dd($DatabaseStorageModel);
                $DatabaseStorageModel->save();
                \Cart::add($cartItem);

                // dd($this, $Cart, $cartItem);
                // $CartItems = \Cart::session($user->id)->getContent();
                // dd($CartItems);
        
                // $return['stock'] = $stock;
                // $return['product'] = $product;
                // $return['cartItems'] = $CartItems;
                return redirect('/products/' . $request->id);//, $return);
                // dd($items);
                

                // $cartItem = $cart->add($request->id, $product->ProductName, $product->SAPrice, $request->Quantity);
                // dd($cartItem);
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
        // Cart::update(456, array(
        //   'name' => 'New Item Name', // new item name
        //   'price' => 98.67, // new item price, price can also be a string format like so: '98.67'
        // ));

        // $user = \Auth::user();
        // $cartItem = \Cart::session($user->id)->getContent()[$id];
        // $product

        // dd($request, $id, $cartItem);
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