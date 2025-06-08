<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelExport implements FromView
{
    protected $data, $labels;

    public function __construct(array $labels, array $data)
    {
        $this->data = $data;
        $this->labels = $labels;
    }

    public function view(): View
    {
        return view('export.excel', [
            'labels' => $this->labels,
            'data' => $this->data
        ]);
    }
}
