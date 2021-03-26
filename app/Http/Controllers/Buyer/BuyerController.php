<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index()
    {
        $buyers = User::all();
        return response()->json(['data' => $buyers], 200);
    }

    public function show(User $buyer)
    {
        return response()->json(['data' => $buyer], 200);
    }
}
