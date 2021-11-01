<?php

namespace App\Services;

use App\Http\Requests\PrizeRequest;
use App\Models\Pos;
use App\Models\Prize;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PrizeOrderService
{
    protected User $user;
    protected Pos $pos;

    public function __construct(User $user)
    {
        $this->user = Auth::user();
        $this->pos = $user->pos()->first();
    }

    /**
     * @param Prize $prize
     * @return bool
     */
    public function order(Prize $prize, PrizeRequest $request): bool
    {
        $posPointsService = new PosPointsService($this->pos);

        if ($posPointsService->lackingPointsToPurchased($prize) === 0) {
            $prize->order()->create(array_merge($request->only('full_name', 'phone', 'email', 'postal_code', 'city', 'address'), [
                'user_id' => $this->user->id,
                'pos_id' => $this->pos->id,
                'value' => $prize->value,
                'saldo' => $posPointsService->saldoPoints(),
                'order_date' => now(),
                'status_id' => 1,
            ]));
        } else {
            return false;
        }
        
        return true;
    }
}
