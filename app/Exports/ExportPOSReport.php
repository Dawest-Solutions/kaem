<?php

namespace App\Exports;

use App\Models\Period;
use App\Models\Pos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportPOSReport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Pos::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'Okres',
            'Główny klient',
            'Numer POS',
            'Nazwa firmy',
            'Przedstawiciel handlowy',
            'Cel bieżący Basic',
            'Cel bieżacy Silver',
            'Cel bieżący Gold',
            'Realizacja bieżąca',
        ];
    }

    /**
     * @param Pos $row
     * @return array
     */
    public function map($row): array
    {
        $threshold_basic = 0;
        $threshold_silver = 0;
        $threshold_gold = 0;
        $turnover = 0;

        foreach ($row->results(Period::getFromSession())->get() as $result) {
            $threshold_basic += $result->threshold_basic;
            $threshold_silver += $result->threshold_silver;
            $threshold_gold += $result->threshold_gold;
            $turnover += $result->turnover;
        }

        return [
            $row->id,
            Period::getFromSession()->number . ' - ' . Period::getFromSession()->name,
            $row->number_pos_main,
            $row->number_pos,
            $row->company_name,
            $row->ph->name,
            $threshold_basic,
            $threshold_silver,
            $threshold_gold,
            $turnover,
        ];
    }
}
