<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\ValidatePurchaseRequest;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\PurchaseDetailsResource;
use App\Purchase;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class PurchaseController extends Controller
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

            $purchases = Purchase::filter(request()->all())
                ->latest("created_at")
                ->paginate($pageSize);

            $total = $purchases->total();

            $purchases = PurchaseResource::collection($purchases);

            $data = Helper::buildData($purchases, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Purchases fetched successfully', 200);
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
    public function store(ValidatePurchaseRequest $request)
    {
        if (!auth()->user()->activated) {

            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }

        $validated = $request->validated();
        $validated['supplier_name'] = Supplier::find($validated['supplier_id'] )->name;
        $validated['user_id'] = auth()->id();
         $validated['staff'] = auth()->user()->username;

        DB::beginTransaction();

        try
        {
            $purchase = Purchase::create($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new PurchaseResource($purchase), 'Purchase was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function show(Purchase $purchase)
    {

        try {

            $purchase = new PurchaseDetailsResource($purchase);

            return Helper::validRequest($purchase, 'specified Purchase was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Purchase $purchase)
    {
        $request->except('user_id');
        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate([

            'supplier_id' => "numeric|exists:suppliers,id",
            'comment' => 'string',

        ]);

        DB::beginTransaction();
        try {
            $validated['updated_by'] = $user;
            $purchase = $purchase->update($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $purchase], 'Purchase was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Purchase $purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(Purchase $purchase)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {
            $purchase->purchaseDetails()->delete();
            $purchase = $purchase->delete();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $purchase], 'Purchase was deleted successfully', 200);
    }
}
