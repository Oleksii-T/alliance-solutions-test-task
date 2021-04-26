<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseApiController as BaseApiController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;

// API authentication
class AuthApiController extends BaseApiController
{
    /**
     * register user
     *
     * @param  Request new user data
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->sendResponse($response, 'Registation successful!');
    }

    /**
     * login user and create new token
     *
     * @param  Request new user data
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // Check email
        $user = User::where('email', $fields['email'])->first();

        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
            return $this->sendError('Bad credits!');
        }

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return $this->sendResponse($response, 'Log in successful!');
    }

    /**
     * delete user tokens
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return $this->sendResponse(true, 'Log out successful!');
    }
}