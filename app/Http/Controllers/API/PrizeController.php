<?php

namespace App\Http\Controllers\API;

use App\Exceptions\IsNotAssigned;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrizeRequest;
use App\Http\Resources\PrizeCollection;
use App\Http\Resources\PrizeDetailsResource;
use App\Http\Resources\PrizeResource;
use App\Models\Prize;
use App\Models\PrizeCategory;
use App\Services\PosPointsService;
use App\Services\PrizeOrderService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PrizeController extends Controller
{
    public function index()
    {
        try {
            $pos = Auth::user()->pos()->first();

            if (is_null($pos)) {
                throw new IsNotAssigned(__('POS is not linked to your account.'), 500);
            }

            $posPointsService = new PosPointsService($pos);
            $prizes = Prize::isVisibility()->get();
            $categories = PrizeCategory::all();

            return $this->success(new PrizeCollection($prizes, $categories, $posPointsService));
        } catch (Exception $ex) {
            return $this->error('Error.');
        }
    }

    public function show(int $id)
    {
        try {
            $prize = Prize::isVisibility()->findOrFail($id);
            $pos = Auth::user()->pos()->firstOrFail();
            $posPointsService = new PosPointsService($pos);

            return $this->success(new PrizeDetailsResource($prize, $posPointsService));
        } catch(ModelNotFoundException $ex){
            if ($ex->getModel() === 'App\Models\Pos'){
                return $this->error(__("You do not have an assigned POS."));
            }
        } catch (Exception $ex) {
            return $this->error("Error.");
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function order(int $id, PrizeRequest $request): JsonResponse
    {
        try {
            $prize = Prize::isVisibility()->findOrFail($id);
            $prizeOrderService = (new PrizeOrderService(Auth::user()));
            
            if ($prizeOrderService->order($prize, $request)) {
                return $this->success('', "Congratulations, you ordered the {$prize->name}");
            }

            return $this->error("You don't have enough points.");

        } catch (Exception $ex) {
            return $this->error('Error.');
        }
    }
}
