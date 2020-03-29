<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $user = User::all()->where('email', $request->email)->where('password', $request->password);
        if ($user->isNotEmpty()) {
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
        //
        $user = '';
        if ($request->email && $request->name && $request->password && $request->role) {
            $user = new User();
            $user->email = $request->email;
            $user->name = $request->name;
            $user->password = $request->password;
            $user->role = $request->role;
            $user->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Requirements are not sufficient',
            ]);
        }
        // $user = User::create($this->validateUserRequest());
        // echo ($user);
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
            'password' => 'required|password',
            'role' => 'required',
        ]);
    }
}
