<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        return redirect('/admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->role == 1) {
            return redirect('/admin');
        }
        return redirect('/admin');
    }

    public function admin()
    {
        if (Auth::user()->role == 1) {
            return redirect('/admin/user/manage');
        }
        if (Auth::user()->role == 3) {
            return redirect('/cust/my-cmc-request');
        }
        if (Auth::user()->role ==4) {
            return redirect('/warehouse/my-cmc-request');
        }
        if (Auth::user()->role ==2) {
            return redirect('/commercial/my-cmc-request');
        }
        return view('home.admin');
    }
}
