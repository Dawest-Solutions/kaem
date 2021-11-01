<?php

namespace App\Services;

use App\Models\Period;
use App\Models\Pos;
use Illuminate\Support\Facades\Auth;

class GoalsService
{
    protected Pos $pos;

    public function __construct(Pos $pos)
    {
        $this->pos = $pos;
    }

    public function getByPeriod(Period $period)
    {
        return $period->load()->get();
    }

    public function getPos()
    {
        return $this
            ->pos
            ->load('posAdditional');
    }

    public function getPosAdditionalByUser(User $user)
    {
        return $this->pos->posAdditional()
            ->where('user_id', $user)
            ->first();
    }

}
