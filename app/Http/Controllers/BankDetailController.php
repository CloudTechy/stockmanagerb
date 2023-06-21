<?php

namespace App\Http\Controllers;

use App\BankDetail;
use App\Helper;
use App\Http\Resources\BankDetailResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class BankDetailController extends Controller
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

            $bankdetails = BankDetail::filter(request()->all())
                ->orderBy('bank', 'asc')
                ->paginate($pageSize);

            $total = $bankdetails->total();

            $bankdetails = BankDetailResource::collection($bankdetails);

            $data = Helper::buildData($bankdetails, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'BankDetails fetched successfully', 200);
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

            "bank" => "required|max:50|string",
            "account_name" => "required|string|min:4",
            "account_number" => "required|numeric|min:10",
            "supplier_id" => "required|numeric|min:1|exists:suppliers,id",

        ]);
        DB::beginTransaction();

        try
        {
            $bankdetail = BankDetail::create($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new BankDetailResource($bankdetail), 'BankDetail was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankDetail $bankdetail
     * @return \Illuminate\Http\Response
     */
    public function show(BankDetail $bankdetail)
    {
        try {

            $bankdetail = new BankDetailResource($bankdetail);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($bankdetail, 'specified BankDetail was fetched successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankDetail $bankdetail
     * @return \Illuminate\Http\Response
     */
    public function edit(BankDetail $bankdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankDetail $bankdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankDetail $bankdetail)
    {
        $request->except('supplier_id');
        $validated = $request->validate([

            "bank" => "max:50|string",
            "account_name" => "string|min:4",
            "account_number" => "numeric|min:10",

        ]);

        DB::beginTransaction();
        try {

            $bankdetail = $bankdetail->update($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $bankdetail], 'BankDetail was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankDetail $bankdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankDetail $bankdetail)
    {
        DB::beginTransaction();
        try {

            $bankdetail = $bankdetail->delete();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $bankdetail], 'BankDetail was deleted successfully', 200);
    }
}
