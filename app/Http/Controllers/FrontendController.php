<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data;

class FrontendController extends Controller
{
    //fetch data
    public function dashboard(){
        $data = Data::all();
        return view('dashboard', compact('data'));
    }
}
