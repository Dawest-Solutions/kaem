<?php

namespace App\Imports;

use App\Models\OrderStatuses;
use App\Models\Pos;
use App\Models\PrizeOrder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OrdersImport implements ToCollection, WithStartRow, WithCalculatedFormulas
{

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * @param Collection $collection
     * @return void
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            PrizeOrder::updateOrInsert([
                'id' => $row[0],
            ], [
                'status_id' => OrderStatuses::where('name', 'like', $row[1])->get()->first()->id,
                'user_id' => $row[2],
                'pos_id' => Pos::where('company_name', 'like', $row[4])->get()->first()->id,
                'prize_id' => $row[7],
                'value' => $row[8],
                'order_date' => $row[9],
                'release_date' => $row[10],
                'days_from_order' => $row[11],
                'days_late' => $row[12],
                'tax_declaration' => $row[13],
            ]);
        }
    }
}
