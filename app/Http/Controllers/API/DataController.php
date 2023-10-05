<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Data;

class DataController extends Controller
{
    //fetch all data
    public function index(){
        $data = Data::select('intensity', 'likelihood', 'relevance', 'end_year', 'country', 'topic', 'region', 'city', 'sector', 'swot', 'pestle', 'start_year')->get();

        return response()->json([
            'status' => 200,
            'data' => $data
        ], 200);
    }
}
