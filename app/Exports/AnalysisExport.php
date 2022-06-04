<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnalysisExport implements FromCollection, WithHeadings
{
    public $data;

    use Exportable;


    public function __construct($data) {
        $this->data = $data;
    }

    public function collection()
    {
        $data = $this->data->get();
        $data = $data->map(function($value){
            $response = $value;
            $response->description = strip_tags($response->description);
            $response->qty = strval($response->qty);
            $response->positifQty = strval($response->positifQty);
            $response->negatifQty = strval($response->negatifQty);
            $response->netralQty = strval($response->netralQty);

            return $response;
        });
        return $data;
    }

    public function headings(): array
    {
        return ["Tanggal", "Analisa", "Sentimen Positif", "Sentimen Negatif", "Sentimen Netral", "Jumlah media"];
    }
}
