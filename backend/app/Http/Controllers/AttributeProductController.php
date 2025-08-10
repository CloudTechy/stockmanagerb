<?php

namespace App\Http\Controllers;

use App\AttributeProduct;
use App\Helper;
use App\Http\Requests\ValidateAttributeProductRequest;
use App\Http\Resources\AttributeProductResource;
use App\Jobs\ProcessProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class AttributeProductController extends Controller
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
            $search = request()->query('search', null);

            $query = AttributeProduct::with([
                'product:id,name,category,pku,image,description,discount,discount_start,discount_end,discontinued',
                'attribute:id,type',
                'user:id,first_name,last_name'
            ]);
            // Apply search if present
            if ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
                })
                ->orWhereHas('attribute', function ($q) use ($search) {
                    $q->where('type', 'like', "%{$search}%");
                });
            }

            $attributeproducts = $query
            ->latest("size")
            ->paginate($pageSize);

        $total = $attributeproducts->total();
        $attributeproducts = AttributeProductResource::collection($attributeproducts);

        $data = Helper::buildData($attributeproducts, $total);

    } catch (\Exception $bug) {
        return $this->exception($bug, 'unknown error', 500);
    }

    return Helper::validRequest($data, 'attributeProducts fetched successfully', 200);
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
    public function store(ValidateAttributeProductRequest $request)
    {

        $validated = $request->validated();
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();

        DB::beginTransaction();

        try
        {
            $attributeproduct = AttributeProduct::create($validated);

            DB::commit();
            ProcessProduct::dispatch();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new AttributeProductResource($attributeproduct), 'AttributeProduct was sent successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AttributeProduct $attributeproduct
     * @return \Illuminate\Http\Response
     */
    public function show(AttributeProduct $attributeproduct)
    {

        try {

            $attributeproduct = new AttributeProductResource($attributeproduct);

            return Helper::validRequest($attributeproduct, 'specified AttributeProduct was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AttributeProduct $attributeproduct
     * @return \Illuminate\Http\Response
     */
    public function edit(AttributeProduct $attributeproduct)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AttributeProduct $attributeproduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AttributeProduct $attributeproduct)
    {
        $request->except('name', 'product_id', 'size');
        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate([

            'attribute_id' => "numeric|exists:attributes,id",
            'purchase_price' => 'numeric',
            'percent_sale' => 'numeric|digits_between:1,100',
            'sale_price' => 'numeric',
            'available_stock' => 'numeric',

        ]);

        DB::beginTransaction();
        try {
            $validated['updated_by'] = $user;
            $attributeproduct = $attributeproduct->update($validated);
            ProcessProduct::dispatch();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $attributeproduct], 'AttributeProduct was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AttributeProduct $attributeproduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(AttributeProduct $attributeproduct)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        DB::beginTransaction();
        try {

            $attributeproduct = $attributeproduct->delete();
            ProcessProduct::dispatch();

            DB::commit();

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $attributeproduct], 'AttributeProduct was deleted successfully', 200);
    }
}
