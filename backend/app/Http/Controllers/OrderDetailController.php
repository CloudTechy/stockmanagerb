<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeProduct;
use App\Helper;
use App\Http\Requests\ValidateOrderDetailRequest;
use App\Http\Resources\OrderDetailResource;
use App\Jobs\ProcessOrder;
use App\Order;
use App\OrderDetail;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class OrderDetailController extends Controller
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

            $orderdetails = OrderDetail::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $orderdetails->total();

            $orderdetails = OrderDetailResource::collection($orderdetails);

            $data = Helper::buildData($orderdetails, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Orderdetails fetched successfully', 200);
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

    public function store(ValidateOrderDetailRequest $request)
    {


        try
        {
            $validated = $request->validated();
            $orders = $validated['orderDetails'];
            $order_id = $validated['order_id'];
            $orderDetails = [];

            foreach ($orders as $id => $value) {
                $val = array_keys($value);
                $id = $val[0];
                $quan = array_values($value);
                $quantityPrice = explode(" ", $quan[0]);
                $quantity = intval($quantityPrice[0]) ;
                $price =  intval($quantityPrice[1]);
                $productAttribute = AttributeProduct::find($id);
                $brand = Attribute::find($productAttribute->attribute_id);
                $product = Product::find($productAttribute->product_id);
                if ($product->discountValidity) {
                    $price = (100 - $product->discount) / 100 * (float) $price;
                    $orderDetail['price'] = (float) $price;
                } else {
                    $orderDetail['price'] = (float) $price;

                }

                //check to see that the stock is not exceeded
                if (($productAttribute->available_stock - $quantity) < 0) {

                    return Helper::invalidRequest($product->name . ': quantity(' . $quantity . ') exceeded the available stock(' . $productAttribute->available_stock . ') ', 400);
                }
                //check if the order has been placed before and update it
                $order_detail_counter = OrderDetail::where(['order_id' => $order_id, 'brand' => $brand->type, 'category' => $product->category, 'size' => $productAttribute->size]);

                if ($order_detail_counter->count() != 0) {
                    $orderdetail = $order_detail_counter->first();
                    $orderdetail->update(['quantity' => $orderdetail->quantity + $quantity]);
                    $orderdetail = OrderDetail::find($orderdetail->id);
                    //update product attribute
                    $productAttribute->update(['available_stock' => $productAttribute->available_stock - $quantity]);
                    // update customer
                    $order = Order::find($order_id);
                    $debit = $orderDetail['price'] * $quantity + (float) $order->customer->owing;

                    $order->customer->update(['owing' => $debit]);

                    array_push($orderDetails, $orderdetail);
                    continue;
                }

                //Build order details
                $orderDetail['order_id'] = $order_id;
                $orderDetail['product'] = $product->name;
                $orderDetail['brand'] = $brand->type;
                $orderDetail['discount'] = $product->discount;
                $orderDetail['category'] = $product->category;
                $orderDetail['quantity'] = $quantity;
                $orderDetail['pku'] = $product->pku;
                $orderDetail['size'] = $productAttribute->size;
                $orderDetail['cost_price'] = $productAttribute->purchase_price;

                $orderdetail = OrderDetail::create($orderDetail);
                $result = $productAttribute->update(['available_stock' => $productAttribute->available_stock - $quantity]);

                array_push($orderDetails, $orderdetail);

            }

            if (!Helper::createInvoice($order_id, 'order')) {

                throw new Exception("Error Processing invoice request", 1);

            }
            ProcessOrder::dispatch();
            DB::commit();

            $orderdetails = collect($orderDetails)->map(function ($row) {
                return OrderDetailResource::make($row)->resolve();

            });

            return Helper::validRequest($orderdetails, 'OrderDetail was sent successfully', 200);
            DB::beginTransaction();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\OrderDetail $orderdetail
     * @return \Illuminate\Http\Response
     */
    public function show(OrderDetail $orderdetail)
    {

        try {

            $orderdetail = new OrderDetailResource($orderdetail);

            return Helper::validRequest($orderdetail, 'specified OrderDetail was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\OrderDetail $orderdetail
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderDetail $orderdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\OrderDetail $orderdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderDetail $orderdetail)
    {
        $request->except('user_id');

        $validated = $request->validate([

            'pku' => 'string|exists:units,name',
            'price' => 'numeric',
            'percent_sale' => 'numeric',
            'quantity' => 'numeric',

        ]);

        DB::beginTransaction();
        try {
            $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
            $validated['updated_by'] = $user;

            if (isset($validated['quantity'])) {

                $validated['available_stock'] = $validated['quantity'];
            }
            if (isset($validated['price'])) {

                $validated['order_price'] = $validated['price'];
            }

            $orderdetail = $orderdetail->update($validated);
            ProcessOrder::dispatch();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $orderdetail], 'OrderDetail was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\OrderDetail $orderdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderDetail $orderdetail)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {

            $orderdetail = $orderdetail->delete();
            ProcessOrder::dispatch();
            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $orderdetail], 'OrderDetail was deleted successfully', 200);
    }
}
