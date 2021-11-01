<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{

    public function query()
    {
        return User::query();
    }

    public function headings(): array
    {
        return [
            'id',
            'Imię',
            'Nazwisko',
            'POS Główny',
            'POS Dodatkowy',
            'Nazwa firmy',
            'Telefon',
            'E-mail',
            'Data urodzenia',
            'Ulica',
            'Numer budynku',
            'Numer mieszkania',
            'Kod pocztowy',
            'Miasto',
            'Gmina',
            'Powiat',
            'Województwo',
            'Urząd skarbowy',
            'Typ opodatkowania',
            'created_at',
            'updated_at',
        ];
    }

    /**
     * @param User $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->first_name,
            $row->last_name,
            $row->pos->number_pos_main,
            $row->pos->number_pos,
            $row->pos->company_name,
            $row->phone,
            $row->email,
            $row->birth_date,
            $row->street,
            $row->building_number,
            $row->apartment_number,
            $row->postal_code,
            $row->city,
            $row->borough,
            $row->district,
            $row->voivodeship,
            $row->tax_office,
            $row->tax_declaration,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
