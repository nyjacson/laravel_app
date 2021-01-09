<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index() {
//        $products = [
//            0 => [
//                'name' => 'iPhone',
//                'category' => 'smart phone',
//                'price' => 1000
//            ],
//            1 => [
//                'name' => 'Gallaxy',
//                'category' => 'tablet',
//                'price' => 1000
//            ],
//            2 => [
//                'name' => 'sony',
//                'category' => 'TV',
//                'price' => 3000
//            ]
//        ];
        $products = Product::all();

        return view('allproducts', compact("products"));
    }
}
