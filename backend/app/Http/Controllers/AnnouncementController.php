<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\Helper;
use App\Http\Requests\ValidateAnnouncementRequest;
use App\Http\Resources\AnnouncementCollection as AnnouncementResource;
use Illuminate\Http\Request;
use \Exception;

class AnnouncementController extends Controller
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

            $announcements = Announcement::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $announcements->total();

            $announcements = AnnouncementResource::collection($announcements);

            $data = Helper::buildData($announcements, $total);

            return Helper::validRequest($data, 'Announcements fetched successfully', 200);

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
    public function store(ValidateAnnouncementRequest $request)
    {
        $validated = $request->validated();
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();
        try
        {

            $announcement = Announcement::create($validated);

            return Helper::validRequest(new AnnouncementResource($announcement), 'Announcement was sent successfully', 200);
        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show(Announcement $announcement)
    {
        try {

            $announcement = new AnnouncementResource($announcement);

            return Helper::validRequest($announcement, 'specified announcement was fetched successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Announcement $announcement)
    {

        $validated = $request->validate([

            'user_id' => 'numeric',
            'topic' => 'unique:announcements|string|min:4|max:255',
            'message' => 'string|min:4',
            'date_start' => 'date_format:Y-m-d|before_or_equal:date_end',
            'date_end' => 'date_format:Y-m-d|after_or_equal:date_start',

        ]);
        try {

            $announcement = $announcement->update($validated);

            return Helper::validRequest(["success" => $announcement], 'Announcement was updated successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        try {

            $announcement = $announcement->delete();

            return Helper::validRequest(["success" => $announcement], 'Announcement was deleted successfully', 200);

        } catch (\Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
