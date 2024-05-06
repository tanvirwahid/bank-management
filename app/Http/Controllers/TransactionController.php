<?php

namespace App\Http\Controllers;

use App\Contracts\FeeCalculatorFactory;
use App\Contracts\TransactionHandlerInterface;
use App\Exceptions\InsufficientBalanceException;
use App\Http\Requests\TransactionRequest;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    public function __construct(
        private FeeCalculatorFactory $feeCalculatorFactory,
        private TransactionHandlerInterface $transactionService
    )
    {
    }

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
                    'transactions' => Transaction::withdrawal()->where('user_id', auth()->id())->get(),
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

    public function deposit(TransactionRequest $request)
    {
        try {
            return response()->json([
                'data' => $this->transactionService->deposit(User::find(auth()->id()), $request->get('amount')),
                'message' => 'Successfully made a deposit'
            ]);
        } catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'message' => 'Transaction failed'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function withdraw(TransactionRequest $request)
    {
        try {

            $user = User::find(auth()->id());

            return response()->json([
                'data' => $this->transactionService->withdraw(
                    $this->feeCalculatorFactory->getFeeCalculator($user->account_type),
                    $user, 
                    $request->get('amount')
                ),
                'message' => 'Transaction successfull'
            ]);
        } 
        catch (InsufficientBalanceException $exception)
        {
            return response()->json([
                'message' => 'Insufficient balance'
            ], JsonResponse::HTTP_FORBIDDEN);
        }
        catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'message' => 'Transaction failed'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
