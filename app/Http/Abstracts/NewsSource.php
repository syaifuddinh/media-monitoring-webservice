<?php

namespace App\Http\Abstracts;

use DB;

class NewsSource 
{
    public static function index()
    {
        $data = [];
        $list = DB::table("rawdata")->select("source AS name")->distinct()->get();
        $data["list"] = $list;

        return $data;
    }
}
