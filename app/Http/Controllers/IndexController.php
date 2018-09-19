<?php

// namespace
namespace App\Http\Controllers;

// use
use App\Models\Plan;

/**
 * Class IndexController
 *
 * @package App\Http\Controllers
 */
class IndexController extends Controller
{
    public function landingpage()
    {
        if(!auth('api')->check()) {
            $plans = Plan::orderBy('price', 'ASC')->with('product')->get();
            return view('landingpage', ['plans' => $plans]);
        } else {
            return redirect('dashboard');
        }

    }
}
