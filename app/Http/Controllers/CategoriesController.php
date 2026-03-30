<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    public function show(){
        $categories = Category::all();
        return response()->json([
            'categories' => $categories,
        ]);
    }
}
