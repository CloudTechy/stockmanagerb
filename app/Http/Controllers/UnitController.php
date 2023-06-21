<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\UnitResource;
use App\Jobs\ProcessUnit;
use App\Unit;
use Illuminate\Http\Request;
use \Exception;

class UnitController extends Controller
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

            $pageUnit = request()->query('pageUnit', 10000000);

            $units = Unit::orderBy('name', 'asc')
                ->paginate($pageUnit);

            $total = $units->total();

            $units = UnitResource::collection($units);

            $data = Helper::buildData($units, $total);

            return Helper::validRequest($data, 'Units fetched successfully', 200);

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

            "name" => "required|unique:Units|max:50|string",

        ]);
        try
        {

            $unit = Unit::create($validated);
            ProcessUnit::dispatch();

            return Helper::validRequest(new UnitResource($unit), 'Unit was sent successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        try {

            $unit = new UnitResource($unit);

            return Helper::validRequest($unit, 'specified Unit was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {

        $validated = $request->validate([

            'name' => 'string|max:50',

        ]);
        try {

            $unit = $unit->update($validated);
            ProcessUnit::dispatch();
            return Helper::validRequest(["success" => $unit], 'Unit was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        try {

            $unit = $unit->delete();
            ProcessUnit::dispatch();
            return Helper::validRequest(["success" => $unit], 'Unit was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
