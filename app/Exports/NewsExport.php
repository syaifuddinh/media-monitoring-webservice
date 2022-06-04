<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewsExport implements FromQuery, WithHeadings
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
    public function headings(): array
    {
        return ["Judul", "Konten", "Tanggal", "Sumber Berita", "Sentimen", "Sentimen Positif", "Sentimen Negatif", "Sentimen Netral"];
    }
}
