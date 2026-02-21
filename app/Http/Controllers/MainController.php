<?php

namespace App\Http\Controllers;

use App\Models\Skate;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {
        $skates = Skate::all();
        return view('index', compact('skates'));
    }
}
