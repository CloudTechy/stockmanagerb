<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\AttributeProduct;
use App\Helper;
use App\Http\Requests\ValidatePurchaseDetailRequest;
use App\Http\Resources\PurchaseDetailResource;
use App\Http\Resources\PurchaseDetailsResource;
use App\Jobs\ProcessPurchase;
use App\Product;
use App\Purchase;
use App\PurchaseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class PurchaseDetailController extends Controller
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

            $purchasedetails = PurchaseDetail::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $purchasedetails->total();

            $purchasedetails = PurchaseDetailResource::collection($purchasedetails);

            $data = Helper::buildData($purchasedetails, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'Purchasedetails fetched successfully', 200);
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

    public function store(ValidatePurchaseDetailRequest $request)
    {

        DB::beginTransaction();

        try
        {

            $validated = $request->validated();
            $validated = $validated['purchaseDetails'];
            if (!auth()->user()->activated) {
                return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
            }

            $purchasedetails = [];

            foreach ($validated as $purchaseDetails => $purchaseDetail) {

                $validated = $purchaseDetail;
                $purchase = Purchase::find($validated['purchase_id']);
                $attribute_id = Attribute::where('type', $validated['brand'])->first()->id;
                $product = Product::where('name', $validated['product']);
                if (!empty($product->count())) {
                    $product_id = $product->first()->id;
                    $prdAttribute = AttributeProduct::where(['product_id' => $product_id, "size" => $validated['size'], "attribute_id" => $attribute_id]);
                    if ($prdAttribute->count() != 0) {
                        //create new purchase detail and update product attribute here
                        $quantity = $prdAttribute->first()->available_stock + $validated['quantity'];
                        $purchasedetail = PurchaseDetail::create($validated);
                        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
                        if ($prdAttribute->update(['available_stock' => $quantity, 'purchase_price' => $validated['price'], 'sale_price' => $validated['sale_price'], 'updated_by' => $user])) {
                            array_push($purchasedetails, $purchasedetail);
                        }

                    } else {
                        $newPurchaseDetail = $validated;
                        $newPurchaseDetail['product_id'] = $product_id;
                        $newPurchaseDetail['attribute_id'] = $attribute_id;
                        $newPurchaseDetail['available_stock'] = $validated['quantity'];
                        $newPurchaseDetail['user_id'] = auth()->id();
                        $newPurchaseDetail['price'] = $validated['price'];
                        $newPurchaseDetail['sale_price'] = $validated['sale_price'];
                        $purchasedetail = PurchaseDetail::create($validated);
                        $productAttribute = AttributeProduct::create($newPurchaseDetail);
                        array_push($purchasedetails, $purchasedetail);

                        //

                    }

                } else {

                    //create new product and product attribute here
                    $newProduct = $validated;
                    $newProduct['name'] = $validated['product'];
                    $newProduct['user_id'] = auth()->id();
                    $newProduct['supplier_id'] = $purchase->supplier_id;

                    $product = Product::create($newProduct);
                    //create the attribute
                    $newPurchaseDetail = $validated;
                    $newPurchaseDetail['product_id'] = $product->id;
                    $newPurchaseDetail['attribute_id'] = $attribute_id;
                    $newPurchaseDetail['available_stock'] = $validated['quantity'];
                    $newPurchaseDetail['user_id'] = auth()->id();
                    $newPurchaseDetail['price'] = $validated['price'];
                    $newPurchaseDetail['sale_price'] = $validated['sale_price'];

                    $purchasedetail = PurchaseDetail::create($validated);
                    $productAttribute = AttributeProduct::create($newPurchaseDetail);
                    array_push($purchasedetails, $purchasedetail);

                }

            }
            ProcessPurchase::dispatch();
            DB::commit();
            $purchasedetails = collect($purchasedetails)->map(function ($row) {
                return PurchaseDetailResource::make($row)->resolve();

            });

            if (!Helper::createInvoice($validated['purchase_id'], 'purchase')) {

                throw new Exception("Error Processing invoice request", 1);

            }

            return Helper::validRequest($purchasedetails, 'PurchaseDetail was sent successfully', 200);

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PurchaseDetail $purchasedetail
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseDetail $purchasedetail)
    {

        try {

            $purchasedetail = new PurchaseDetailResource($purchasedetail);

            return Helper::validRequest($purchasedetail, 'specified PurchaseDetail was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PurchaseDetail $purchasedetail
     * @return \Illuminate\Http\Response
     */
    public function edit(PurchaseDetail $purchasedetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PurchaseDetail $purchasedetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseDetail $purchasedetail)
    {
        $request->except('user_id');

        $validated = $request->validate([

            'pku' => 'string|exists:units,name',
            'price' => 'numeric',
            'sale_price' => 'numeric',
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

                $validated['purchase_price'] = $validated['price'];
            }

            $purchasedetail = $purchasedetail->update($validated);

            DB::commit();
            ProcessPurchase::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $purchasedetail], 'PurchaseDetail was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PurchaseDetail $purchasedetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseDetail $purchasedetail)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {

            $purchasedetail = $purchasedetail->delete();

            DB::commit();
            ProcessPurchase::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $purchasedetail], 'PurchaseDetail was deleted successfully', 200);
    }
}
