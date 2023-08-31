<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function landing()
    {
        $products = Product::take(3)->get();
        return view('welcome', compact('products'));
    }
}
