<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class UserInterfaceController extends Controller
{
    public function index()
    {
        $facilities = Facility::all();

        return view('home', compact('facilities'));
    }

}
