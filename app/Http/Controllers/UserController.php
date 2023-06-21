<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\User as UserResource;
use App\Jobs\ProcessUser;
use App\User;
use Illuminate\Http\Request;
use \Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $page = request()->query('page', 1);

            $pageSize = request()->query('pageSize', 10000000);

            $users = User::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $users->total();

            $users = UserResource::collection($users);

            $data = Helper::buildData($users, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Users fetched successfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->activated || !auth()->user()->isSuperAdmin) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        $this->validate($request, [
            'username' => 'required|min:3|string|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|string',
            'address' => '|min:6|string',
            'number' => 'required|min:6|unique:users,number',
            'user_level_id' => 'required|numeric',
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',

        ]);
        try
        {

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
            ProcessUser::dispatch();

            return Helper::validRequest(['token' => $token], $message = 'User registration was successful', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        try {

            $user = new UserResource($user);

            return Helper::validRequest($user, 'specified Type was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->activated || !auth()->user()->isSuperAdmin) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }

        $request->except('username', 'email', 'number', 'password');

        $validated = $request->validate([

            'address' => '|min:6|string',
            'user_level_id' => 'numeric',
            'first_name' => 'min:3|string',
            'last_name' => 'min:3|string',

        ]);
        try {

            $user = $user->update($validated);
            ProcessUser::dispatch();

            return Helper::validRequest(["success" => $user], 'Type was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        try {

            $user = $user->update(['activated' => false]);
            ProcessUser::dispatch();

            return Helper::validRequest(["success" => $user], 'user was deactivated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }
}
