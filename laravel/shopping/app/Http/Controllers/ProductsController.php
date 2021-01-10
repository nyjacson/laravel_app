<?php

namespace App\Http\Controllers;
use App\Product;
use App\Cart;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProductsController extends Controller
{
    public function index() {

        $products = Product::all();
        return view('allproducts', compact("products"));
    }

    public function addProductToCart(Request $request, $id) {

        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        $product = Product::find($id);
        $cart->addItem($id, $product);

        $request->session()->put('cart', $cart);
//        $request->session()->forget('cart');

        return redirect()->route('allProducts');
    }

    public function showCart() {

        $cart = Session::get('cart');

        // cart is not empty
        if($cart) {
//            dump($cart);
            return view('cartProducts', ['cartItems' => $cart]);
        // cart is empty
        } else {

            return redirect()->route('allProducts');
        }
    }

    public function deleteItemFromCart(Request $request, $id) {
        $cart = $request->session()->get("cart");
        if(array_key_exists($id, $cart->items)) {
            unset($cart->items[$id]);
        }

        $prevCart = $request->session()->get("cart");
        $updatedCart = new Cart($prevCart);
        $updatedCart->updatePriceAndQuantity();

        $request->session()->put('cart', $updatedCart);

        return redirect()->route('cartProducts');
    }
}
