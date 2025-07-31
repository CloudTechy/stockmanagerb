<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Http\Resources\StatusResource;
use App\Status;
use Illuminate\Http\Request;
use \Exception;

class StatusController extends Controller
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

            $pageStatus = request()->query('pageStatus', 10000000);

            $statuses = Status::orderBy('type', 'asc')
                ->paginate($pageStatus);

            $total = $statuses->total();

            $statuses = StatusResource::collection($statuses);

            $data = Helper::buildData($statuses, $total);

            return Helper::validRequest($data, 'statuses fetched successfully', 200);

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

            "type" => "required|unique:statuses|max:50|string",

        ]);
        try
        {

            $status = Status::create($validated);

            return Helper::validRequest(new StatusResource($status), 'Status was sent successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function show(Status $status)
    {
        try {

            $status = new StatusResource($status);

            return Helper::validRequest($status, 'specified Status was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function edit(Status $status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Status $status)
    {

        $validated = $request->validate([

            'type' => 'unique:statuses|string|max:50',

        ]);
        try {

            $status = $status->update($validated);

            return Helper::validRequest(["success" => $status], 'Status was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        try {

            $status = $status->delete();

            return Helper::validRequest(["success" => $status], 'Status was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
