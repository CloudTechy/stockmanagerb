<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\ValidateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Http\Resources\SupplierDetails;
use App\Jobs\ProcessSupplier;
use App\Supplier;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class SupplierController extends Controller
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

            $suppliers = Supplier::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $suppliers->total();

            $suppliers = SupplierResource::collection($suppliers);

            $data = Helper::buildData($suppliers, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Suppliers fetched successfully', 200);
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
    public function store(ValidateSupplierRequest $request)
    {
        $request->except('updated_by');
        $validated = $request->validated();
        $validated['staff'] = auth()->user()->username;
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();
        DB::beginTransaction();
        try
        {
            $supplier = Supplier::create($validated);

            if ((!$request->bank_name == null) && (!$request->acc_name == null) && (!$request->acc_number == null)) {

                $supplier->bankDetails()->create([

                    "bank" => $request->bank_name,
                    "account_name" => $request->acc_name,
                    "account_number" => $request->acc_number,

                ]);
            }
            DB::commit();
            ProcessSupplier::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(new SupplierDetails($supplier), 'Supplier was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(Supplier $supplier)
    {
        try {

            $supplier = new SupplierDetails($supplier);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($supplier, 'specified Supplier was fetched successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(Supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {

        $request->except('user_id');
        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate([

            "name" => "string|min:4|max:255",
            "address" => "nullable|string|min:4",
            "city" => "nullable|string|min:3|max:255",
            "number" => "string|min:3|max:255",
            "email" => "email|min:3|",
            "contact_person" => "nullable|string|min:3",
            "owed" => "nullable|numeric",
            "country" => "nullable|string|min:3|max:255",
            "is_stock_available" => "boolean",
            "bank_name" => "nullable|string",
            "acc_number" => "required_with:bank_name",
            "acc_name" => "required_with:bank_name",

        ]);

        DB::beginTransaction();
        try {
            $validated['updated_by'] = $user;

            $supplier = $supplier->update($validated);

            DB::commit();
            ProcessSupplier::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $supplier], 'Supplier was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {
            $supplier->bankDetails()->delete();

            $supplier = $supplier->delete();

            DB::commit();
            ProcessSupplier::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $supplier], 'Supplier was deleted successfully', 200);
    }
}
