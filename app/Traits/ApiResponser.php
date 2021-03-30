<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection;

trait ApiResponser{
    private function successResponse($data, $code){
        return response()->json($data,$code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error'=>$message, 'code'=>$code],$code);
    }

    protected function showAll($collection, $code = 200){
        return $this->successResponse($collection, $code);
    }

    protected function showOne($instance, $code = 200){
        return $this->successResponse($instance, $code);
    }
}
