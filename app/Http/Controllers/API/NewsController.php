<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(){
        try {
            $news = News::all();

            return $this->success(NewsResource::collection($news));
        } catch (Exception $ex) {
            return $this->error('Błąd.');
        }
    }
}
