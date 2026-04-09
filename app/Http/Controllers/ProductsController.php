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
    public function showId($id){
        $product = Product::with(['category', 'images'])->find($id);
        return response()->json([
            'products' => $product
        ]);
    }

    public function store(Request $request){
        // ** validation **
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required',
            'stock' => 'required|integer',
            'category'    => 'required|integer|exists:categories,id',
            'images.*' => 'image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // ** Create Product **
        $product = Product::create([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'cat_id' => $request->category,
        ]);

        // ** Create Images **
        $uploadedImages = [];
        if($request->hasFile('images')){
            foreach($request->file('images') as $file){
                $path = $file->store('products', 'public');
                $image = $product->images()->create(['image_path' => $path]);
                $uploadedImages[] = [
                'id' => $image->id,
                'url' => asset('storage/' . $image->image_path)
            ];
            }
        }

        // ** Return Response **
        return response()->json([
            'message' => 'Product created successfully',
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'stock' => $product->stock,
                'category' => $product->category,
                'images' => $uploadedImages
            ]
        ],201);

    }
}
