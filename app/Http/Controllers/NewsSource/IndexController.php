<?php

namespace App\Http\Controllers\NewsSource;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\NewsSource;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $data = null;
        $data = NewsSource::index();

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
