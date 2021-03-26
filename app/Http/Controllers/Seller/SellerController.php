<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = User::all();
        return response()->json(['data' => $sellers], 200);
    }

    public function show(User $seller)
    {
        return response()->json(['data' => $seller], 200);
    }
}
