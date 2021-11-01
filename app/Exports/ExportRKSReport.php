<?php

namespace App\Exports;

use App\Models\Period;
use App\Models\Rks;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportRKSReport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Rks::query();
    }

    public function headings(): array
    {
        return [
            'ID',
            'RKS',
            'Cel bieżący Basic',
            'Cel bieżacy Silver',
            'Cel bieżący Gold',
            'Realizacja bieżąca',
            'Ilość zarejestrowanych klientów',
            'Ilość przypisanych klientów',
        ];
    }

    /**
     * @param Rks $row
     * @return array
     */
    public function map($row): array
    {
        $results = $row->getTotalResults(Period::getFromSession());
        $count = 0;

        foreach ($row->phs as $ph) {
            foreach ($ph->poses as $pos) {
                $count += $pos->results->count();
            }
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
