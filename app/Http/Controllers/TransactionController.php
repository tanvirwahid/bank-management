<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function index()
    {
        try {

            $user = auth()->user();

            return response()->json([
                'data' => [
                    'transactions' => Transaction::where('user_id', $user->id)->get(),
                    'balance' => $user->balance
                ],
                'message' => 'Successfully fetched transactions'
            ]);
        } catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'message' => 'Failed fetching transaction'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getDeposits()
    {
        try {
            return response()->json([
                'data' => [
                    'transactions' => Transaction::deposit()->where('user_id', auth()->id())->get(),
                ],
                'message' => 'Successfully fetched transactions'
            ]);
        } catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'message' => 'Failed fetching transaction'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getWithdrawal()
    {
        try {
            return response()->json([
                'data' => [
                    'transactions' => Transaction::widrawal()->where('user_id', auth()->id())->get(),
                ],
                'message' => 'Successfully fetched transactions'
            ]);
        } catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'message' => 'Failed fetching transaction'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
