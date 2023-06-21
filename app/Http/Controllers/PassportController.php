<?php

namespace App\Http\Controllers;

use App\Helper;
use App\User;
use Illuminate\Http\Request;
use \Exception;

class PassportController extends Controller
{
    /**
     * Handles Registration Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|min:3|string|unique:users',
            'email' => 'required|email|unique:users|string',
            'password' => 'required|min:6|string',
            'address' => '|min:6|string',
            'number' => 'required|min:6|unique:users,number',
            'user_level_id' => 'required|numeric',
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',

        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'number' => $request->number,
            'user_level_id' => $request->user_level_id,
            'address' => $request->address,
            // 'city' => $request->city,
        ]);

        $token = $user->createToken('stockManager')->accessToken;

        return Helper::validRequest(['token' => $token], $message = 'User registration was successful', 200);
    }

    /**
     * Handles Login Request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $credentials = $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        try {
            if (auth()->validate($credentials)) {
                $token = auth()->user()->createToken('stockManager')->accessToken;
                return Helper::validRequest(['token' => $token], $message = 'User login was successful', 200);
            } else {
                throw new Exception("authentication error", 1);

            }

        } catch (Exception $exception) {
            $message = "User login failed";

            return $this->exception($exception, $message, 401);
        }
    }

}
