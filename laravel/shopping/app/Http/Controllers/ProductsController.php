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

//    public function createOrder() {
//        $cart = Session::get('cart');
//
//        // cart is not empty
//        if($cart) {
//            $date = date('Y-m-d H:i:s');
//            $newOrderArray = array('status' => 'on_hold', 'date' => $date, 'del_date' => $date, 'price' => $cart->totalPrice);
//            $created_order = DB::table('orders')->insert($newOrderArray);
//            $order_id = DB::getPdo()->lastInsertId();
//
//            foreach ($cart->items as $cart_item) {
//                $item_id = $cart_item['data']['id'];
//                $item_name = $cart_item['data']['name'];
//                $item_price = $cart_item['data']['price'];
//                $newItemsInCurrentOrder = array('item_id' => $item_id, 'order_id' => $order_id, 'item_name' => $item_name, 'item_price' => $item_price);
//                $created_order_items = DB::table('order_items')->insert($newItemsInCurrentOrder);
//            }
//
//            // delete cart
//            Session::forget('cart');
//            Session::flush();
//            return redirect()->route('allProducts')->withsuccess('Thanks for choosing us');
//
//        } else {
//
//            return redirect()->route('allProducts');
//        }
//    }

    public function checkoutProducts() {
        return view('checkoutProducts');
    }

    public function createNewOrder(Request $request) {
        $cart = Session::get('cart');
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $address = $request->input('address');
        $zip = $request->input('zip');
        $phone = $request->input('phone');
        $email = $request->input('email');

        // cart is not empty
        if($cart) {
            $date = date('Y-m-d H:i:s');
            $newOrderArray = array('status' => 'on_hold', 'date' => $date, 'del_date' => $date, 'price' => $cart->totalPrice, 'first_name' => $first_name,
                'last_name' => $last_name, 'zip' => $zip, 'address' => $address, 'email' => $email, 'phone' => $phone);
            $created_order = DB::table('orders')->insert($newOrderArray);
            $order_id = DB::getPdo()->lastInsertId();

            foreach ($cart->items as $cart_item) {
                $item_id = $cart_item['data']['id'];
                $item_name = $cart_item['data']['name'];
                $item_price = $cart_item['data']['price'];
                $newItemsInCurrentOrder = array('item_id' => $item_id, 'order_id' => $order_id, 'item_name' => $item_name, 'item_price' => $item_price);
                $created_order_items = DB::table('order_items')->insert($newItemsInCurrentOrder);
            }

            // delete cart
            Session::forget('cart');
            Session::flush();
            return redirect()->route('allProducts')->withsuccess('Thanks for choosing us');

        } else {

            return redirect()->route('allProducts');
        }
    }
}
