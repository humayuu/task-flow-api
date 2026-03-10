<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * For User Register
     */
    public function UserRegister(Request $request)
    {
        $userValidate = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email|string|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        if ($userValidate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $userValidate->errors()->all(),
            ], 422);
        }

        DB::beginTransaction();

        try {

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'User Register Successfully',
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error in user Registration ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to register new user',
            ], 500);
        }
    }


    /**
     * For User Register
     */
    public function UserLogin(Request $request)
    {
        $userValidate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($userValidate->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $userValidate->errors()->all(),
            ], 422);
        }


        try {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return response()->json([
                    'status' => true,
                    'message' => 'User Login Successfully',
                    'token' => Auth::user()->createToken('authToken')->plainTextToken,
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Wrong email or password',
                ], 401);
            }
        } catch (Exception $e) {

            Log::error('Error in user Login ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to Login user',
            ], 500);
        }
    }


    /**
     * For User Logout
     */

    public function UserLogout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Logout Successfully',
            ], 200);
        } catch (Exception $e) {

            Log::error('Error in user Logout ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to Logout user',
            ], 500);
        }
    }
}
