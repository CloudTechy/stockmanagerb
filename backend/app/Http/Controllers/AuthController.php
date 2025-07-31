<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\ValidateUserRequest;
use App\Http\Resources\User as UserResource;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use App\Notifications\UserRegistered;
use Illuminate\Support\Facades\DB;
use \Exception;

use function PHPSTORM_META\type;

class AuthController extends Controller {
	// use SendsPasswordResetEmails, ResetsPasswords {
	// 	SendsPasswordResetEmails::broker insteadof ResetsPasswords;
	// 	ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
	// }
	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}
	/**
	 * Register a new user
	 */
	function register(Request $request) {
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
            'city' => $request->city
        ]);

        if ($token = Auth::attempt($request->only(['email','password']))) {
        	return Helper::validRequest(['token' => $token],'User registration was successful', 200)->header('Authorization', $token);
			} else {
				return Helper::invalidRequest('Authentication failed','Login attempt failed validation',  401);
			}


	}

	/**
	 * Login user and return a token
	 */
	function login(Request $request) {
		$credentials = $this->validate($request, [

            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
	    try
		{
			
			if ($token = Auth::attempt($credentials)) {
				return response()->json(['status' => 'success', 'token' => $token], 200)->header('Authorization', $token);
			} else {
				return Helper::invalidRequest('Authentication failed','Login attempt failed validation',  401);
			}

		} catch (\Exception $bug) {
			return $this->exception($bug, 'Login failed', 500);
		}

	}

	/**
	 * Logout User
	 */
	function logout() {
		try
		{
			Auth::logout();
			return response()->json([
				'status' => 'success',
				'msg' => 'Logged out Successfully.',
			], 200);

		} catch (\Exception $bug) {
			return $this->exception($bug, 'unknown error', 500);
		}
	}

	/**
	 * Get authenticated user
	 */
	function user(Request $request) {
		try
		{
			$user = User::find(Auth::user()->id);
			return response()->json([
				'status' => 'success',
				'data' => new UserResource($user),
			]);

		} catch (\Exception $bug) {
			return $this->exception($bug, 'unknown error', 500);
		}

	}

	/**
	 * Refresh JWT token
	 */
	function refresh() {
		try
		{
			if ($token = Auth::refresh()) {
				return response()
					->json(['status' => 'successs', 'token' => $token], 200)
					->header('Authorization', $token);
			} else {
				return Helper::invalidRequest(['error' => 'refresh_token_error'], 'Authentication error', 401);
			}

		} catch (\Exception $bug) {
			return $this->exception($bug, 'unknown error', 401);
		}
	}

	/**
	 * Return auth guard
	 */
	function guard() {
		return Auth::guard();
	}

	function sendPasswordResetLink(Request $request) {
		$validated = $request->validate([
			'email' => 'required|email|exists:users,email',
		]);
		try
		{
			$req = new Request($validated);
			return $this->sendResetLinkEmail($req);

		} catch (\Exception $bug) {
			return $this->exception($bug, 'Unknown error', 500);
		}

	}
	function sendResetLinkResponse(Request $request, $response) {
		try
		{
			return Helper::validRequest($response, 'Password reset email sent.', 200);

		} catch (\Exception $bug) {
			return $this->exception($bug, 'Unknown error', 500);
		}

	}
	function sendResetLinkFailedResponse(Request $request, $response) {
		return Helper::invalidRequest(['error' => 'Email failed to send'], 'Email could not be sent to this email address.', 401);
	}
	function callResetPassword(Request $request) {
		return $this->reset($request);
	}
	function resetPassword($user, $password) {
		$user->password = Hash::make($password);
		$user->save();

		event(new PasswordReset($user));
	}
	function sendResetResponse(Request $request, $response) {
		return Helper::validRequest(['success' => 'password reset success'], 'Password reset successfully.', 200);
	}
	function sendResetFailedResponse(Request $request, $response) {
		return Helper::invalidRequest(['error' => 'Token is Invalid'], 'Failed, Invalid Token.', 401);
	}
}
