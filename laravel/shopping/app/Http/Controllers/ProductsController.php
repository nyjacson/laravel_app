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

        $products = Product::paginate(3);
        return view('allproducts', compact("products"));
    }

    public function menProducts() {
        $products = DB::table('products')->where('type', 'men')->get();
        return view('menProducts', compact("products"));
    }

    public function womenProducts() {
        $products = DB::table('products')->where('type', 'women')->get();
        return view('womenProducts', compact("products"));
    }

    public function search(Request $request) {
        $searchText = $request->get('searchText');
        // this is how to use pagination with where
        $products = Product::where('name', "like", $searchText."%")->paginate(3);
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

    public function increaseSingleProduct(Request $request, $id) {
        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        $product = Product::find($id);
        $cart->addItem($id, $product);
        $request->session()->put('cart', $cart);

        return redirect()->route('cartProducts');
    }

    public function decreaseSingleProduct(Request $request, $id) {
        $prevCart = $request->session()->get('cart');
        $cart = new Cart($prevCart);

        if ($cart->items[$id]['quantity'] > 1) {
            $product = Product::find($id);
            $cart->items[$id]['quantity'] = $cart->items[$id]['quantity'] - 1;
            // remove $ from price (getPriceAttribute)
            $price = (int) str_replace("$", "", $product['price']);
            $cart->items[$id]['totalSinglePrice'] = $cart->items[$id]['quantity'] * $price;
            $cart->updatePriceAndQuantity();

            $request->session()->put('cart', $cart);
        }

        return redirect()->route('cartProducts');
    }
}
