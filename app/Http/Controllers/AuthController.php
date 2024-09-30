<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         description="User's name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="password",
     *         in="query",
     *         description="User's password",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="201", description="User login successful"),
     *     @OA\Response(response="422", description="Validation errors"),
     *     @OA\Response(response="404", description="Invalid username or password"),
     *     @OA\Response(response="500", description="Server Error")
     * )
     */
    function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'password' => 'required|max:255',
        ], [
            'username' => 'The username field must not be empty',
            'password' => 'The password field must not be empty',
        ]);

        if ($validator->fails())
            return response()->json([
                'status' => false,
                'msg' => $validator->customMessages
            ], 422);

        try {
            if (!Auth::attempt(['username' => $request->username, 'password' => $request->password]))
                return response()->json([
                    'status' => false,
                    'msg' => 'Invalid username or password'
                ], 404);

            $user = auth()->user();

            $token = $user->createToken($request->username . '_token');
            $plainTextToken = str_replace($token->accessToken->id . '|', '', $token->plainTextToken);

            return response()->json([
                'status' => true,
                'token' => $plainTextToken,
                'user' => $user
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'status' => false,
                'msg' => 'Server error'
            ], $th->getCode());
        }
    }

    function logout(): JsonResponse {
        try {
            auth()->user()->tokens()->delete();

            return response()->json([
                'status' => true,
                'msg' => 'Logout successful'
            ], 205);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());

            return response()->json([
                'status' => false,
                'msg' => 'Server error'
            ], $th->getCode());
        }
    }
}
