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
            return $this->jsonResponse(true, 'Lists of Users.', $user);
        } else {
            return $this->jsonResponse(false, 'Currently, there is no any Users.', $user);
        }
    }

    public function store()
    {
        $user = User::create($this->validateUserRequest());
        return $this->jsonResponse(true, 'User has been created successfully.', $user);
    }

    public function show(User $user)
    {
        return $this->jsonResponse(true, 'Data of an individual User.', $user);
    }

    public function update(User $user)
    {

        $user->update($this->validateUserRequest());
        return $this->jsonResponse(true, 'User has been updated.', $user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return $this->jsonResponse(true, 'User has been deleted successfully.');
    }

    public function login(Request $request)
    {
        $user = User::all()->where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token['token'] = $user->createToken('KarnaliHome')->accessToken;
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

    private function jsonResponse($success = false, $message = '', $data = null)
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ]);
    }
}
