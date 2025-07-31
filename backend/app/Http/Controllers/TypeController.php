<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\TypeResource;
use App\Type;
use Illuminate\Http\Request;
use \Exception;

class TypeController extends Controller
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

            $pageType = request()->query('pageType', 10000000);

            $types = Type::orderBy('name', 'asc')
                ->paginate($pageType);

            $total = $types->total();

            $types = TypeResource::collection($types);

            $data = Helper::buildData($types, $total);

            return Helper::validRequest($data, 'Types fetched successfully', 200);

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

            "name" => "required|unique:Types|max:50|string",

        ]);
        try
        {

            $type = Type::create($validated);

            return Helper::validRequest(new TypeResource($type), 'Type was sent successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function show(Type $type)
    {
        try {

            $type = new TypeResource($type);

            return Helper::validRequest($type, 'specified Type was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Type $type)
    {

        $validated = $request->validate([

            'name' => 'unique:types|string|max:50',

        ]);
        try {

            $type = $type->update($validated);

            return Helper::validRequest(["success" => $type], 'Type was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
        try {

            $type = $type->delete();

            return Helper::validRequest(["success" => $type], 'Type was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
