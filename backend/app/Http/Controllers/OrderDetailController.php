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

    public function storev0(ValidateOrderDetailRequest $request)
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
    public function store(ValidateOrderDetailRequest $request)
{
    DB::beginTransaction();

    try {
        $validated     = $request->validated();
        $orders        = $validated['orderDetails'];
        $orderId       = $validated['order_id'];
        $orderDetails  = [];
        $totalDebit    = 0.0;

        // Collect attribute_product IDs from the posted structure
        $attributeProductIds = array_map(function ($value) {
            $keys = array_keys($value);
            return $keys[0] ?? null;
        }, $orders);
        $attributeProductIds = array_values(array_filter($attributeProductIds, fn($v) => !is_null($v)));

        if (empty($attributeProductIds)) {
            DB::rollBack();
            return Helper::invalidRequest('No order items supplied', 422);
        }

        // Preload all needed data in bulk (1 query) and key by id
        $attributeProducts = AttributeProduct::with(['attribute', 'product'])
            ->whereIn('id', $attributeProductIds)
            ->get()
            ->keyBy('id');

        // Ensure every ID exists
        foreach ($attributeProductIds as $apId) {
            if (!isset($attributeProducts[$apId])) {
                DB::rollBack();
                return Helper::invalidRequest("Invalid item id: {$apId}", 422);
            }
        }

        // Load order + customer once
        $order = Order::with('customer')->find($orderId);
        if (!$order || !$order->customer) {
            DB::rollBack();
            return Helper::invalidRequest('Order or customer not found', 404);
        }

        // Preload existing order details for this order and key by composite (brand|category|size)
        $existingDetails = OrderDetail::where('order_id', $orderId)->get();
        $existingMap = $existingDetails->keyBy(function ($od) {
            return $od->brand . '|' . $od->category . '|' . $od->size;
        });

        foreach ($orders as $value) {
            // Parse the weird shape: [{id} => "qty price"]
            $idKey = array_keys($value)[0];
            $raw   = (string) array_values($value)[0];

            // Defensive parsing
            $parts = preg_split('/\s+/', trim($raw));
            if (count($parts) < 2) {
                DB::rollBack();
                return Helper::invalidRequest("Bad quantity/price format for item {$idKey}", 422);
            }

            $quantity   = (int) $parts[0];
            $unitPrice  = (float) $parts[1];
            if ($quantity <= 0 || $unitPrice < 0) {
                DB::rollBack();
                return Helper::invalidRequest("Invalid quantity/price for item {$idKey}", 422);
            }

            /** @var \App\Models\AttributeProduct $productAttribute */
            $productAttribute = $attributeProducts[$idKey];
            $brand   = $productAttribute->attribute;   // assumes ->type
            $product = $productAttribute->product;     // assumes fields used below

            // Apply discount if valid
            $effectivePrice = $product->discountValidity
                ? (float) $unitPrice * (100 - (float) $product->discount) / 100.0
                : (float) $unitPrice;

            // ATOMIC stock decrement (prevents race: check+update in one statement)
            // If not enough stock, affected rows == 0 -> fail gracefully
            $affected = AttributeProduct::where('id', $productAttribute->id)
                ->where('available_stock', '>=', $quantity)
                ->decrement('available_stock', $quantity);

            if ($affected === 0) {
                DB::rollBack();
                return Helper::invalidRequest(
                    $product->name . ': quantity(' . $quantity . ') exceeded the available stock(' . $productAttribute->available_stock . ') ',
                    400
                );
            }

            // Composite key to detect same line (brand + category + size)
            $key = $brand->type . '|' . $product->category . '|' . $productAttribute->size;

            if (isset($existingMap[$key])) {
                // Update existing detail quantity
                $existing = $existingMap[$key];
                $existing->increment('quantity', $quantity);
                // refresh local map copy so later duplicates in the same request accumulate properly
                $existing->refresh();
                $existingMap[$key] = $existing;

                $orderDetails[] = $existing;
            } else {
                // Create a new order detail row
                $payload = [
                    'order_id'   => $orderId,
                    'product'    => $product->name,
                    'brand'      => $brand->type,
                    'discount'   => $product->discount,
                    'category'   => $product->category,
                    'quantity'   => $quantity,
                    'pku'        => $product->pku,
                    'size'       => $productAttribute->size,
                    'cost_price' => $productAttribute->purchase_price,
                    'price'      => $effectivePrice,
                ];

                $created = OrderDetail::create($payload);
                $existingMap[$key] = $created; // so duplicates in the same request hit the increment path
                $orderDetails[] = $created;
            }

            // Accumulate total debit (one write later)
            $totalDebit += ($effectivePrice * $quantity);
        }

        // One atomic increment for customer's owing
        if ($totalDebit > 0) {
            $order->customer()->increment('owing', $totalDebit);
        }

        // Commit all DB changes before calling helpers that may do their own commits
        DB::commit();

        // Run heavy/side-effect work AFTER commit so the request isn’t blocked by jobs or helper commits
        // Keep your structure: still call the same helper & job
        if (!Helper::createInvoice($orderId, 'order')) {
            // If invoice creation fails we still return an error to the client (same behavior as your try/throw),
            // but the order detail writes are already committed (same as your old autocommit behavior).
            return Helper::invalidRequest('Error Processing invoice request', 500);
        }

        // If you have Laravel 8.74+, consider ->afterCommit() when dispatching inside transactions.
        ProcessOrder::dispatch($orderId);

        // Build response payload exactly like before
        $orderdetails = collect($orderDetails)->map(function ($row) {
            return OrderDetailResource::make($row)->resolve();
        });

        return Helper::validRequest($orderdetails, 'OrderDetail was sent successfully', 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return $this->exception($e, 'unknown error', 500);
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
