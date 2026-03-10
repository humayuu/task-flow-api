<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();


        return response()->json([
            'status' => true,
            'message' => 'Successfully retrieved users',
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Successfully retrieved user',
                'user' => $user,
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in retrieved user ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieved user',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $userValidate = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email',
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

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'User updated Successfully',
            ], 200);
        } catch (Exception $e) {
            DB::rollBack();

            Log::error('Error in user updated ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to updated new user',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);

            $user->delete();

            return response()->json([
                'status' => true,
                'message' => 'User Deleted Successfully',
            ], 200);
        } catch (Exception $e) {
            Log::error('Error in delete user ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to delete user',
            ], 500);
        }
    }
}