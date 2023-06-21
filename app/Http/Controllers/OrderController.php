<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Requests\ValidateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderDetailsResource;
use App\Order;
use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class OrderController extends Controller
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

            $orders = Order::filter(request()->all())
                ->latest('created_at')
                ->paginate($pageSize);

            $total = $orders->total();

            $orders = OrderResource::collection($orders);

            $data = Helper::buildData($orders, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Orders fetched successfully', 200);
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
    public function store(ValidateOrderRequest $request)
    {
        if (!auth()->user()->activated) {

            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }


        $validated = $request->validated();
        $validated['customer_name'] = Customer::find($validated['customer_id'])->name;
        $validated['user_id'] = auth()->id();
        $validated['staff'] = auth()->user()->username;


        DB::beginTransaction();

        try
        {
            $order = Order::create($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new OrderDetailsResource($order), 'Order was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {

        try {

            $order = new OrderDetailsResource($order);

            return Helper::validRequest($order, 'specified Order was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->except('user_id');
        $user = auth()->user()->username;
        $validated = $request->validate([

            'customer_id' => "numeric|exists:customers,id",
            'comment' => 'string',

        ]);

        DB::beginTransaction();
        try {
            $validated['updated_by'] = $user;
            $order = $order->update($validated);

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $order], 'Order was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {
            $order->orderDetails()->delete();
            $order = $order->delete();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $order], 'Order was deleted successfully', 200);
    }
}
