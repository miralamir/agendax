<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CineController extends Controller
{
    public function index()
    {
        return view('cine.index');
    }
}
