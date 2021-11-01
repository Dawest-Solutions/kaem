<?php

namespace App\Exports;

use App\Models\Period;
use App\Models\Ph;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportPHReport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Ph::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'Okres',
            'Przedstawiciel handlowy',
            'Cel bieżący Basic',
            'Cel bieżacy Silver',
            'Cel bieżący Gold',
            'Realizacja bieżąca',
            'Ilość zarejestrowanych klientów',
            'Ilość przypisanych klientów',
        ];
    }

    /**
     * @param Ph $row
     * @return array
     */
    public function map($row): array
    {
        $results = $row->getTotalResults(Period::getFromSession());
        $count = 0;

        foreach ($row->poses as $pos) {
            $count += $pos->results->count();
        }

        return [
            $row->id,
            Period::getFromSession()->number . ' - ' . Period::getFromSession()->name,
            $row->name,
            $results->sum('threshold_basic'),
            $results->sum('threshold_silver'),
            $results->sum('threshold_gold'),
            $results->sum('turnover'),
            $count,
            $count,
        ];
    }
}
