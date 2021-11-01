<?php

namespace App\Exports;

use App\Models\PrizeOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping
{

    public function query()
    {
        return PrizeOrder::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'Nagroda',
            'POS',
            'Uczestnik',
            'Wartość',
            'Data zamówienia',
            'Status',
            'Działalność',
            'Adresat',
            'Numer telefonu adresata',
            'Email adresata',
            'Adres dostawy',
            'Kod pocztowy',
            'Miasto dostawy',
        ];
    }

    /**
     * @param PrizeOrder $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->prize->model,
            $row->pos->number_pos,
            $row->user->first_name . ' ' . $row->user->last_name,
            $row->value,
            $row->order_date,
            $row->status->name,
            $row->tax_declaration,
            $row->full_name,
            $row->phone,
            $row->email,
            $row->address,
            $row->postal_code,
            $row->city,
        ];
    }
}
