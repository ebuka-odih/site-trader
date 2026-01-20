<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportController extends Controller
{
    /**
     * Display the support page
     */
    public function index(): View
    {
        return view('dashboard.support.index');
    }
}


