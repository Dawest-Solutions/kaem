<?php

namespace App\Http\Controllers\API;

use App\Exceptions\IsNotAssigned;
use App\Http\Controllers\Controller;
use App\Http\Resources\GoalResource;
use App\Http\Resources\GoalsResultsCollection;
use App\Http\Resources\PosResource;
use App\Http\Resources\UserPointsResource;
use App\Http\Resources\PrizeOrderResource;
use App\Http\Resources\UserResource;
use App\Models\Import;
use App\Models\Period;
use App\Models\Pos;
use App\Models\Result;
use App\Services\GoalsService;
use App\Services\PosPointsService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        try {
            $user = Auth::user()->load('pos');

            return $this->success(new UserResource($user));
        } catch (Exception $ex) {
            return $this->error('Błąd.', $ex->getCode());
        }
    }

    public function points(): JsonResponse
    {
        try {
            $pos = Auth::user()->pos()->first();

            if (is_null($pos)) {
                throw new IsNotAssigned(__('POS is not linked to your account.'), 500);
            }

            $posPointsService = new PosPointsService($pos);

            return $this->success(new UserPointsResource($posPointsService));

        } catch (IsNotAssigned $ex) {
            return $this->error($ex->getMessage(), $ex->getCode());
        } catch (Exception $ex) {
            return $this->error('Błąd.', $ex->getCode());
        }
    }

    public function prizes(): JsonResponse
    {
        try {
            $prizeOrders = Auth::user()
                ->prizeOrders()
                ->with('prize')
                ->orderByDesc('order_date')
                ->get();

            return $this->success(PrizeOrderResource::collection($prizeOrders));
        } catch (IsNotAssigned $ex) {
            return $this->error($ex->getMessage(), $ex->getCode());
        } catch (Exception $ex) {
            return $this->error('Błąd.', $ex->getCode());
        }
    }

    public function posAdditional()
    {
        try {
            $pos = Pos::with('posAdditional')
                ->where('user_id', Auth::user()->id)
                ->first();

            return $this->success(new PosResource($pos));
        } catch (Exception $ex) {
            return $this->error('Błąd.');
        }
    }

    public function goals(Pos $pos = null): JsonResponse
    {
        $serachNetwork = false;

        try {
            if (is_null($pos)) {
                $pos = Auth::user()->pos()->first();
                $serachNetwork = true;
            }

            if (is_null($pos)) {
                throw new IsNotAssigned(__('POS is not linked to your account.'), 500);
            }

            $pos->load('posAdditional');

            $period = Period::with([
                'results' => function ($query) use ($serachNetwork, $pos) {
                    if ($serachNetwork && $pos->isMain && $pos->isNetwork) {
                        $query->where('type', 'siec');
                    }else {
                        $query->where('type', 'firma');
                    }
                    $query->where('number_pos', $pos->number_pos);
                }])->get();

            $period->map(function(Period $period){
                $updated_at  = Import::where('period_id', $period->id)->latest()->first()->date ?? null;
                $period->results->map(function (Result $result) use ($updated_at){
                     $result->updated_at = $updated_at;

                     return $result;
                });
                
                return $period;
            });

            return $this->success(new GoalsResultsCollection($period, $pos));
        } catch (IsNotAssigned $ex) {
            return $this->error($ex->getMessage(), $ex->getCode());
        } catch (Exception $ex) {
            return $this->error("Błąd. {$ex->getMessage()}");
        }
    }
}
