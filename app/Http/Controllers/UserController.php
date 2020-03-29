<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::all()->where('email', $request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Users.',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User did not match',
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $user = User::create($this->validateUserRequest());
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User has been created successfully.',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create User',
            ]);
        }
    }

    public function validateUserRequest()
    {
        return request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);
    }
}
