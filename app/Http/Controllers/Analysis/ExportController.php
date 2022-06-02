<?php

namespace App\Http\Controllers\Analysis;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\Analysis;
use App\Exports\AnalysisExport;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade\Pdf;
use Excel;

class ExportController extends Controller
{
    public function excel(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = $request->input('page');
        $length = $request->input('length');
        $paging = [];
        $keyword = null;
        $data = Analysis::query($keyword, $startDate, $endDate, $paging);
        $data = $data->select(
            "analysis.date",
            "analysis.description",
            DB::raw("COALESCE(newsSummary.qty, 0) AS qty")
        );
        return Excel::download(new AnalysisExport($data), 'Analisa.xlsx');
    }

    public function pdf(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $page = $request->input('page');
        $length = $request->input('length');
        $paging = [];
        $keyword = null;
        $data = Analysis::query($keyword, $startDate, $endDate, $paging)
        ->select(
            "analysis.date",
            "analysis.description",
            DB::raw("COALESCE(newsSummary.qty, 0) AS qty")
        )
        ->get()
        ->toArray();
        $pdf = PDF::loadView('analysis.index', ["data" => $data]);
        return $pdf->download('Analisa.pdf');
    }
}
