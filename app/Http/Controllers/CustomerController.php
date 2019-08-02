<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Helper;
use App\Http\Requests\ValidateCustomerRequest;
use App\Http\Resources\CustomerResource;
<<<<<<< HEAD
use App\Jobs\ProcessCustomer;
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
use App\User;
use Illuminate\Http\Request;
use \Exception;

class CustomerController extends Controller
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

            $customers = Customer::filter(request()->all())
                ->latest()
                ->paginate($pageSize);

            $total = $customers->total();

            $customers = CustomerResource::collection($customers);

            $data = Helper::buildData($customers, $total);

            return Helper::validRequest($data, 'Customers fetched successfully', 200);

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
    public function store(ValidateCustomerRequest $request)
    {
        $request->except('updated_by');
        $validated = $request->validated();
        if (!auth()->user()->activated) {
            return Helper::inValidRequest('User not activated', 'Unauthorized Access!', 400);
        }
        $validated['user_id'] = auth()->id();
        try
        {

            $customer = Customer::create($validated);
<<<<<<< HEAD
            ProcessCustomer::dispatch();
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(new CustomerResource($customer), 'Customer was sent successfully', 200);
        } catch (Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        try {

            $customer = new CustomerResource($customer);

            return Helper::validRequest($customer, 'specified Customer was fetched successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
<<<<<<< HEAD
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

        $request->except('user_id');
        $user = auth()->user()->first_name . ' ' . auth()->user()->last_name;
        $validated = $request->validate([

            "name" => "string|max:255",
            "address" => "string",
            "city" => "string|max:255",
            "number" => "string|max:255",
            "notes" => "string",
            "owning" => "numeric",

        ]);
        try {
            $validated['updated_by'] = $user;

            $customer = $customer->update($validated);
<<<<<<< HEAD
            ProcessCustomer::dispatch();
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(["success" => $customer], 'Customer was updated successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        if (!Helper::userIsSuperAdmin()) {
            return Helper::inValidRequest('User not Unauthorized or not Activated.', 'Unauthorized Access!', 400);
        }
        try {

            $customer = $customer->delete();
<<<<<<< HEAD
            ProcessCustomer::dispatch();
=======
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return Helper::validRequest(["success" => $customer], 'Customer was deleted successfully', 200);

        } catch (Exception $bug) {

            return $this->exception($bug, 'unknown error', 500);
        }

    }
}
