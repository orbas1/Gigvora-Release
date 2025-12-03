<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminEntranceController extends Controller
{
    public function __invoke(Request $request)
    {
        $target = admin_path('dashboard');

        if (!Auth::check()) {
            session(['url.intended' => $target]);

            return redirect()->guest(route('login'));
        }

        if (Auth::user()->user_role !== 'admin') {
            return redirect()->route('timeline');
        }

        return redirect($target);
    }
}
