<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\SizeResource;
use App\Jobs\ProcessSize;
use App\Size;
use App\AttributeProduct;
use Illuminate\Http\Request;
use \Exception;

class SizeController extends Controller
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

            $sizes = Size::orderBy('name', 'asc')
                ->paginate($pageSize);

            $total = $sizes->total();

            $sizes = SizeResource::collection($sizes);

            $data = Helper::buildData($sizes, $total);

            return Helper::validRequest($data, 'Sizes fetched successfully', 200);

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

            "name" => "required|unique:sizes|max:50|string",

        ]);
        try
        {

            $size = Size::create($validated);
            ProcessSize::dispatch();

            return Helper::validRequest(new SizeResource($size), 'Size was sent successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function show(Size $size)
    {
        try {

            $size = new SizeResource($size);

            return Helper::validRequest($size, 'specified Size was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function edit(Size $size)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Size $size)
    {
        $validated = $request->validate([

            'name' => 'string|max:50',
            'description' => 'string',

        ]);
        try {

            if ($request['update_products']) {
                
                $attributeProducts = AttributeProduct::where('size', $size->name)->get();
                $size->update($validated);
                foreach ($attributeProducts as $attributeProduct) {
                    $attributeProduct->update(['size' => $validated['name']]);
                }
            }
            ProcessSize::dispatch();

            return Helper::validRequest(["success" => $size], 'Size was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Size  $size
     * @return \Illuminate\Http\Response
     */
    public function destroy(Size $size)
    {
        try {

            $size = $size->delete();
            ProcessSize::dispatch();

            return Helper::validRequest(["success" => $size], 'Size was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
