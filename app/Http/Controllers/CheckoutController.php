<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cart;
use App\Transaction;
use App\Mail\CheckoutMail;
use Auth;
use Mail;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store()
    {
        // logic store/tampilan setelah di checkout
        $carts = Cart::where('user_id', Auth::user()->id);
        // untuk ambil produk ketika di checkout
        $cartUser = $carts->get();
        // untuk menambahkan produk
        $transaction = Transaction::create([
            'user_id' => Auth::user()->id
        ]);

        foreach ($cartUser as $cart){
            $transaction->detail()->create([
                'product_id' => $cart->product_id,
                'qty' => $cart->qty
            ]);
        }

        //RestFull API MAILGUN
        Mail::to($carts->first()->user->email)->send(new CheckoutMail($cartUser));
        // setelah user mencheckout produk di cart
        Cart::where('user_id', AUTH::user()->id)->delete();
        return redirect('/');
    }
}
