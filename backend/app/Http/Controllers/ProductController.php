<?php

namespace App\Http\Controllers;

use App\AttributeProduct;
use App\Helper;
use App\Http\Requests\ValidateProductRequest;
use App\Http\Resources\ProductResource;
use App\Jobs\ProcessProduct;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \Exception;

class ProductController extends Controller
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

            $products = Product::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $products->total();

            $products = ProductResource::collection($products);

            $data = Helper::buildData($products, $total);

            return Helper::validRequest($data, 'products fetched successfully', 200);

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
    public function store(ValidateProductRequest $request)
    {
        $request->except('updated_by');
        $validated = $request->validated();
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();
         $validated['staff'] = auth()->user()->username;

        DB::beginTransaction();
        try
        {
            $product = Product::create($validated);
            $validated['product_id'] = $product->id;
            AttributeProduct::create($validated);
            DB::commit();
            ProcessProduct::dispatch();
            return Helper::validRequest(new ProductResource($product), 'Product was sent successfully', 200);
        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        try {

            $product = new ProductResource($product);

            return Helper::validRequest($product, 'specified Product was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {

        $request->except('user_id', 'name');
        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate([

            "category" => "string|exists:categories,name",
            "pku" => "string|exists:units,name",
            "discount" => 'numeric|digits_between:0,100',
            "discount_start" => 'required_unless:discount,|date_format:Y-m-d H:i:s.u|before_or_equal:date_end',
            "discount_end" => 'required_unless:discount,|date_format:Y-m-d H:i:s.u|after_or_equal:date_start',
            'description' => "string|max:500",
            'supplier_id' => "numeric|exists:suppliers,id",
            'discontinued' => "boolean",
            'image' => "image",

        ]);

        try {
            $validated['updated_by'] = $user;

            $product = $product->update($validated);
            ProcessProduct::dispatch();
            return Helper::validRequest(["success" => $product], 'Product was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {

        DB::beginTransaction();
        try {
            $attributes = AttributeProduct::where('product_id', "=", $product->id)->delete();

            $product = $product->delete();

            DB::commit();
            ProcessProduct::dispatch();
            return Helper::validRequest(["success" => $product], 'Product was deleted successfully', 200);

        } catch (\Exception $bug) {
            DB::rollback();
            return $this->exception($bug, 'unknown error', 500);
        }

    }

    public function image(Request $request, Product $product)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        if ($request->hasFile('image')) {

            if ($request->file('image')->isValid()) {

                $file = $request->file('image');

                $file->move('uploads', $file->getClientOriginalName());

                $validated['image'] = $file->getClientOriginalName();
            }
        }
        try {
            $product = $product->update($validated);
            ProcessProduct::dispatch();
            return Helper::validRequest(["success" => $product], 'Product was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
