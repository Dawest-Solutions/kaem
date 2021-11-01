<?php

namespace App\Exports;

use App\Models\Prize;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PrizesExport implements FromQuery, WithHeadings, WithMapping
{

    public function query()
    {
        return Prize::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'Kategoria',
            'Nazwa',
            'Model',
            'Wartość',
            'Opis',
            'Zdjęcie',
            'Status',
            'Widoczność',
        ];
    }

    /**
     * @param Prize $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->category->name,
            $row->name,
            $row->model,
            $row->value,
            $row->description,
            $row->photo,
            $row->status,
            $row->visibility,
        ];
    }
}
