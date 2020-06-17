<?php

namespace App\Http\Controllers\API\Transaction;

use Validator;
use App\Http\Controllers\Controller;
use App\Transaction;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;


class TransactionsController extends Controller
{    
    
    public function index(Request $request, Transaction $transactions)
    {

        $transactions = $transactions->gettransactions($request);
        return response()->json([
            'result' => [
                'total' => $transactions->total(),
                'rows' => $transactions->items(),
            ],
        ]);
    }

 
    public function store(Request $request)
    {

        $rules = [
            'title' => 'required',
            'rate' => 'required',
            'qty' => 'required',
            'description' => 'required',
            'type' => 'required',
            'author' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $transaction = Transaction::create($request->all());
           
            return response()->json([
                'result' => $transaction,
            ],201);
        } else {
            return response()->json([
                'result' => $validator->messages(),
            ],422);
        }
    }
  
    public function show(Transaction $transaction)
    {

        return $transaction;
        return response()->json([
            'result' => $transaction,
        ], 200);
    }
  
    public function update(Transaction $transaction, Request $request)
    {
        $rules = [
            'title' => 'required',
            'rate' => 'required',
            'qty' => 'required',
            'description' => 'required',
            'type' => 'required',
            'author' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if (!$validator->fails()) {
            $transaction->update($request->all());
            return response()->json([
                'result' => $transaction,
            ], 200);
        } else {
            return response()->json([
                'result' => $validator->messages(),
            ],422);
        }
    }

 
    public function destroy(Transaction $transaction)
    {

        $transaction->delete();
        return response()->json([
            'status' => 'success',
            'result' => 'null',
        ], 200);
    }
}