<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Transaction as TransactionResource;
use App\Http\Resources\TransactionCollection;
use App\Models\Transaction;

class TransactionController extends ApiController
{
    
    public function index()
    {
        $transactions = Transaction::all();
        return $this->showAll(new TransactionCollection($transactions));
    }

    public function show(Transaction $transaction)
    {
        return $this->showOne(new TransactionResource($transaction));
    }

}
