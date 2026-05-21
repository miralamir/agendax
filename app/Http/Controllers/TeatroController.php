<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeatroController extends Controller
{
    public function index()
    {
        return view('teatro.index');
    }
}
