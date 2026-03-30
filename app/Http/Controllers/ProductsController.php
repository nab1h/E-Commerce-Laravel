<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function show(){
        $products = Product::with(['category', 'images'])->get();
        return response()->json([
            'products' => $products
        ]);
    }
}
