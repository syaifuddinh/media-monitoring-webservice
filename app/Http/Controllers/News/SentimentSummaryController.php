<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\NewsChart;
use Illuminate\Http\Request;

class SentimentSummaryController extends Controller
{
    public function index(Request $request)
    {
        $data = null;
        $list = [];
        $keyword = $request->input('keyword');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $summary = NewsChart::index($keyword, $startDate, $endDate);
        $positifAmount = 0;
        $negatifAmount = 0;
        $netralAmount = 0;
        $positif = 0;
        $negatif = 0;
        $netral = 0;
        foreach($summary["interval"] as $index => $date) {
            $positifAmount += $summary["positif"][$index];
            $negatifAmount += $summary["negatif"][$index];
            $netralAmount += $summary["netral"][$index];
        }
        $grandtotal = $positifAmount + $negatifAmount + $netralAmount;

        if($grandtotal > 0) {
            $positif = $positifAmount / $grandtotal * 100;
            $negatif = $negatifAmount / $grandtotal * 100;
            $netral = $netralAmount / $grandtotal * 100;

            $positif = number_format((float)$positif, 2, '.', '');
            $negatif = number_format((float)$negatif, 2, '.', '');
            $netral = number_format((float)$netral, 2, '.', '');
        }

        $data["percentage"] = [
            "positif" => $positif,
            "negatif" => $negatif,
            "netral" => $netral
        ];

        return response()->json([
            "success" => true,
            "message" => "Sukses",
            "data" => $data
        ]);
    }
}
