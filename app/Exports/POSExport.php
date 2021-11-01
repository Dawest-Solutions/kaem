<?php

namespace App\Exports;

use App\Models\Pos;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class POSExport implements FromQuery, WithHeadings, WithMapping
{

    public function query()
    {
        return Pos::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'user_id',
            'ph_id',
            'number_pos_main',
            'number_pos',
            'company_name',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @param Pos $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->user_id,
            $row->ph_id,
            $row->number_pos_main,
            $row->number_pos,
            $row->company_name,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
