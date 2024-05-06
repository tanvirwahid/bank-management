<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreationRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function store(UserCreationRequest $request)
    {
        try {
            return response()->json([
                'data' => User::create([
                    'name' => $request->get('name'),
                    'account_type' => $request->get('account_type'),
                    'email' => $request->get('email'),
                    'password' => Hash::make($request->get('password'))
                ]),
                'message' => 'Successfully created user'
            ]);
        } catch (Exception $exception)
        {
            Log::error($exception);

            return response()->json([
                'data' => $exception->getMessage(),
                'message' => 'User creation failled'
            ], $exception->status ?? JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
