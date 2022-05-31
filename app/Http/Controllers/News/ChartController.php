<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\NewsChart;
use Illuminate\Http\Request;


class ChartController extends Controller
{
    public function index(Request $request)
    {
        $data = null;
        $list = [];
        $keyword = null;
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $data = NewsChart::index($keyword, $startDate, $endDate);

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
