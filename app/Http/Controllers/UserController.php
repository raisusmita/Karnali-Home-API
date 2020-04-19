<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index()
    {
        $user = User::all();
        if ($user->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Lists of Users.',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Currently, there is no any Users.',
            ]);
        }
    }

    public function store()
    {
        $user = User::create($this->validateUserRequest());
        return response()->json([
            'success' => true,
            'message' => 'User has been created successfully.',
            'data' => $user,
        ]);
    }

    public function show(User $user)
    {
        return response()->json([
            'success' => true,
            'message' => 'Data of an individual User',
            'data' => $user,
        ]);
    }

    public function update(User $user)
    {

        $user->update($this->validateUserRequest());
        return response()->json([
            'success' => true,
            'message' => 'User has been updated',
            'data' => $user,
        ]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'success' => true,
            'message' => 'User has been deleted successfully.',
        ]);
    }

    // function list() {
    //     $user = User::all();
    //     if ($user) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Lists of Users.',
    //             'data' => $user,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'No Users',
    //         ]);
    //     }
    // }

    public function login(Request $request)
    {
        $user = User::all()->where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token['token'] = $user->createToken('MyApp')->accessToken;
            $token['name'] = $user->name;
            return response()->json([
                'success' => true,
                'message' => 'Login Success.',
                'data' => $user,
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User did not match',
            ]);
        }
    }

    // public function register(Request $request)
    // {
    //     $user = User::create($this->validateUserRequest());
    //     if ($user) {
    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User has been created successfully.',
    //             'data' => $user,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Failed to create User',
    //         ]);
    //     }
    // }

    // public function updateUser(Request $user)
    // {
    //     dd($user);
    //     // dd(request());
    //     // $user = User::find($request->id);
    //     // $user->update($this->validateUserRequest());
    //     // if ($user) {
    //     //     return response()->json([
    //     //         'success' => true,
    //     //         'message' => 'User has been updated successfully.',
    //     //         'data' => $user,
    //     //     ]);
    //     // } else {
    //     //     return response()->json([
    //     //         'success' => false,
    //     //         'message' => 'Failed to update User',
    //     //     ]);
    //     // }
    // }

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
