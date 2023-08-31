<?php

namespace App\Http\Controllers;

use Auth;
use App\Cart;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        return view('cart.index', compact('carts'));
    }
    
    public function store(Request $request)
    {
        $duplicate = Cart::where('product_id', 
        $request->product_id)->first();

        if($duplicate) {
            return redirect('/cart')->with('error', 'Barang Sudah Ada DiKeranjang!');
        }
        Cart::create([
            'user_id' => Auth::user()->id,
            'product_id' => $request->product_id,
            'qty' => 1
        ]);
        return redirect('/cart')->with('success', 'Barang Berhasil Ditambahkan Ke Keranjang Anda!');
    }

    public function update(Request $request, $id)
    {
        Cart::where('id', $id)->update([
            'qty' => $request->quantity
        ]);
        return respons()->json([
            'success' => true
        ]);
        
    }



    public function delete($id)
    {
        DB::table('carts')->where('id', $id)->delete();
        return redirect('cart')->with('success', 'Barang berhasil dihapus');
        
    }
}