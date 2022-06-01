<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\News;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $data = null;
        $list = [];
        $count = 0;
        $total = 0;
        $keyword = $request->input('keyword');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $sentiment = $request->input('sentiment');
        $newsSource = $request->input('newsSource');
        $page = $request->input('page');
        $length = $request->input('length');
        $page = $page ? $page : 1;
        $length = $length ? $length : 10;
        $paging = [
            "page" => $page,
            "length" => $length
        ];
        $data = News::index($keyword, $startDate, $endDate, $paging, $sentiment, $newsSource);

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }


    public function show($id)
    {
        $data = null;
        try {
            $data = News::show($id);
        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage(),
                "data" => null
            ], 422); 
        }

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
