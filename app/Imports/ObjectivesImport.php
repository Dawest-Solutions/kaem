<?php

namespace App\Imports;

use App\Models\Period;
use App\Models\Pos;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class ObjectivesImport implements ToCollection, WithStartRow, WithCalculatedFormulas
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
            // check if pos exists
            if (Pos::where('number_pos_main', $row[0])
                ->where('number_pos', $row[1])
                ->exists())
            {
                $period = Period::where('number', $row[2])->first();

                Result::updateOrInsert([
                    'number_pos_main' => $row[0],
                    'number_pos' => $row[1],
                    'period_id' => $period->id,
                    'type' => $row['3'],
                ], [
                    'threshold_basic' => round($row[4]),
                    'basic_points' => round($row[5]),
                    'threshold_silver' => round($row[6]),
                    'silver_points' => round($row[7]),
                    'threshold_gold' => round($row[8]),
                    'gold_points' => round($row[9]),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
