<?php

namespace App\Http\Controllers;

use App\Attribute;
use App\Helper;
use App\Http\Resources\AttributeResource;
use App\Jobs\ProcessBrand;
use Illuminate\Http\Request;
use \Exception;

class AttributeController extends Controller
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

            $attributes = Attribute::orderBy('type', 'asc')
                ->paginate($pageSize);

            $total = $attributes->total();

            $attributes = AttributeResource::collection($attributes);

            $data = Helper::buildData($attributes, $total);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500); 
        }
        return Helper::validRequest($data, 'attributes fetched successfully', 200);
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

            "type" => "required|unique:attributes|max:50|string",
            "description" => "string",

        ]);
        try
        {

            $attribute = Attribute::create($validated);
            ProcessBrand::dispatch();
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }

        return Helper::validRequest(new AttributeResource($attribute), 'Attribute created successfully', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function show(Attribute $attribute)
    {

        try {

            $attribute = new AttributeResource($attribute);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($attribute, 'specified Attribute was fetched successfully', 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Attribute $attribute)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([

            'type' => 'string|max:50',
            "description" => "string",

        ]);
        try {

            $attribute = $attribute->update($validated);
            ProcessBrand::dispatch();

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $attribute], 'Attribute was updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Attribute  $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attribute $attribute)
    {
        try {

            $attribute = $attribute->delete();
            ProcessBrand::dispatch();

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest(["success" => $attribute], 'Attribute was deleted successfully', 200);
    }
}
