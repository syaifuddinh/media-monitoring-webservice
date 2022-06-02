<?php

namespace App\Http\Controllers\News;

use App\Http\Controllers\Controller;
use App\Http\Abstracts\News;
use App\Exports\NewsExport;
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
        $sentiment = $request->input('sentiment');
        $newsSource = $request->input('newsSource');
        $page = $request->input('page');
        $length = $request->input('length');
        $paging = [];
        $keyword = null;
        $data = News::query($keyword, $startDate, $endDate, $paging, $sentiment, $newsSource);
        $data = $data->select("title", "textcontent", "published_date", "source", "sentiment", "sentpos", "sentneg", "sentneutral");
        return Excel::download(new NewsExport($data), 'Berita.xlsx');
    }

    public function pdf(Request $request)
    {
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
        $sentiment = $request->input('sentiment');
        $newsSource = $request->input('newsSource');
        $page = $request->input('page');
        $length = $request->input('length');
        $paging = [];
        $keyword = null;
        $data = News::query($keyword, $startDate, $endDate, $paging, $sentiment, $newsSource)->get()->toArray();
        $pdf = PDF::loadView('news.index', ["data" => $data]);
        return $pdf->download('Berita.pdf');
    }
}
