<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class NewsExport implements FromQuery
{
    public $data;

    use Exportable;


    public function __construct($data) {
        $this->data = $data;
    }

    public function query()
    {
        return $this->data;
    }
}
