<?php

namespace App\Http\Controllers;

use App\Category;
<<<<<<< HEAD
use App\Events\UpdateCategory;
use App\Helper;
use App\Http\Resources\CategoryResource;
use App\Jobs\ProcessCategory;
=======
use App\Helper;
use App\Http\Resources\CategoryResource;
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
use Illuminate\Http\Request;
use \Exception;

class CategoryController extends Controller
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

            $categories = Category::orderBy('name', 'asc')
                ->paginate($pageSize);

            $total = $categories->total();

            $categories = CategoryResource::collection($categories);

            $data = Helper::buildData($categories, $total);

            return Helper::validRequest($data, 'Categories fetched successfully', 200);

        } catch (Exception $bug) {

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

            "name" => "required|unique:Categories|max:50|string",
            'description' => "string",

        ]);
        try
        {

            $category = Category::create($validated);
<<<<<<< HEAD
            ProcessCategory::dispatch();
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(new CategoryResource($category), 'Category created successfully', 200);
        } catch (Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {

        try {

            $category = new CategoryResource($category);

            return Helper::validRequest($category, 'specified Category was fetched successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {

        $validated = $request->validate([

            'name' => 'string|max:50',
            'description' => 'string',

        ]);
        try {

            $category = $category->update($validated);
<<<<<<< HEAD
            event(new UpdateCategory());
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(["success" => $category], 'Category was updated successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {

            $category = $category->delete();
<<<<<<< HEAD
            event(new UpdateCategory());
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(["success" => $category], 'Category was deleted successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }
}
