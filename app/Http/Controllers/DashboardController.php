<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function headmaster()
    {
        return view('dashboard.headmaster');
    }

    public function it()
    {
        return view('dashboard.it');
    }
} 