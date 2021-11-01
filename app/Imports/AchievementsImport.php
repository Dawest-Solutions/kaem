<?php

namespace App\Imports;

use App\Models\Period;
use App\Models\Pos;
use App\Models\Result;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class AchievementsImport implements ToCollection, WithStartRow, WithCalculatedFormulas
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
            if(Pos::where('number_pos', $row[0])
                ->exists())
            {
                $period = Period::where('number', $row[1])->first();

                // check if objectives was imported
                if (Result::where('number_pos', $row[0])
                    ->where('period_id', $period->id)
                    ->exists())
                {
                    Result::updateOrInsert([
                        'number_pos' => $row[0],
                        'period_id' => $period->id,
                        'type' => $row[2],
                    ], [
                        'turnover' => round($row[3]),
                        'lacking_points_basic' => round($row[4]),
                        'lacking_points_silver' => round($row[5]),
                        'lacking_points_gold' => round($row[6]),
                        'inactive_points' => round($row[7]),
                        'active_points' => round($row[8]),
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
                else {
                    Log::warning('Brak Celu dla POS: ' . $row[0] . ' w edycji nr: ' . $row[1]);
                    echo 'Brak Celu dla POS: ' . $row[0] . ' w edycji nr: ' . $row[1];
                }
            }
        }
    }
}
