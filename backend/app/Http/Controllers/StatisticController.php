<?php

namespace App\Http\Controllers;

use App\Helper;
use App\Statistic;
use Illuminate\Http\Request;
use \Exception;

class StatisticController extends Controller
{

    public function customer() 
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30000);
            $customers = Statistic::customerFilter(request()->all())
                ->paginate($pageSize);
            $total = $customers->total();
            $customers = $customers->getCollection();
            $data = Helper::buildData($customers, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function supplier()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 10000000);
            $suppliers = Statistic::supplierFilter(request()->all())
                ->paginate($pageSize);
            $total = $suppliers->total();
            $suppliers = $suppliers->getCollection();
            $data = Helper::buildData($suppliers, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function transaction()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30);
            $transactions = Statistic::transactionFilter(request()->all())
                ->paginate($pageSize);
            $total = $transactions->total();
            $transactions = $transactions->getCollection();
            $data = Helper::buildData($transactions, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function invoice()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30);
            $invoices = Statistic::invoiceFilter(request()->all())
                ->paginate($pageSize);
            $total = $invoices->total();
            $invoices = $invoices->getCollection();
            $data = Helper::buildData($invoices, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function purchase()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30);
            $purchases = Statistic::purchaseFilter(request()->all())
                ->paginate($pageSize);
            $total = $purchases->total();
            $purchases = $purchases->getCollection();
            $data = Helper::buildData($purchases, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function product()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30);
            $products = Statistic::productFilter(request()->all())
                ->paginate($pageSize);
            $total = $products->total();
            $products = $products->getCollection();
            $data = Helper::buildData($products, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
    public function order()
    {
        try {
            $page = request()->query('page', 1);
            $pageSize = request()->query('pageSize', 30);
            $orders = Statistic::orderFilter(request()->all())
                ->paginate($pageSize);
            $total = $orders->total();
            $orders = $orders->getCollection();
            $data = Helper::buildData($orders, $total);

        } catch (\Exception $bug) {
            return $this->exception($bug, 'unknown error', 500);
        }
        return Helper::validRequest($data, 'fetch success', 200);
    }
}
