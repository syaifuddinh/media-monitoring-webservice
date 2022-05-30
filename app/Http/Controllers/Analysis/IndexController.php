<?php

namespace App\Http\Controllers\Analysis;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\Analysis;
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
        $page = $request->input('page');
        $length = $request->input('length');
        $page = $page ? $page : 1;
        $length = $length ? $length : 10;
        $paging = [
            "page" => $page,
            "length" => $length
        ];

        $data = Analysis::index($keyword, $startDate, $endDate, $paging);

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }

    public function store(Request $request)
    {
        $date = $request->input('date');
        $description = $request->input('description');
        try {
            Analysis::store($date, $description);
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
            "data" => null
        ]);        
    }

    public function update(Request $request, $id)
    {
        $date = $request->input('date');
        $description = $request->input('description');

        try {
            Analysis::update($date, $description, $id);
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
            "data" => null
        ]); 
    }

    public function show($id)
    {
        $data = null;
        try {
            $data = Analysis::show($id);
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

    public function destroy($id)
    {
        try {
            Analysis::destroy($id);
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
            "data" => null
        ]); 
    }
}
