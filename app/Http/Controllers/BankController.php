<?php

namespace App\Http\Controllers;

use App\Bank;
use App\Helper;
use App\Http\Resources\BankResource;
use App\Jobs\ProcessBank;
use Illuminate\Http\Request;
use \Exception;

class BankController extends Controller
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

            $banks = Bank::orderBy('name', 'asc')
                ->paginate($pageSize);

            $total = $banks->total();

            $banks = BankResource::collection($banks);

            $data = Helper::buildData($banks, $total);

            return Helper::validRequest($data, 'Banks fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
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
        $validated = $request->validate([

            "name" => "required|unique:banks|max:50|string",

        ]);
        try
        {

            $bank = Bank::create($validated);
            ProcessBank::dispatch();

            return Helper::validRequest(new BankResource($bank), 'Bank created successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function show(Bank $bank)
    {

        try {

            $bank = new BankResource($bank);

            return Helper::validRequest($bank, 'specified bank was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function edit(Bank $bank)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bank $bank)
    {
        $validated = $request->validate([

            'name' => 'unique:banks|string|max:50',

        ]);
        try {

            $bank = $bank->update($validated);
            ProcessBank::dispatch();

            return Helper::validRequest(["success" => $bank], 'Bank was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Bank  $bank
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        try {

            $bank = $bank->delete();
            ProcessBank::dispatch();

            return Helper::validRequest(["success" => $bank], 'Bank was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }
}
