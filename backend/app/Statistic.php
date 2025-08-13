<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Statistic extends Model
{
    protected $fields = ['count', 'order_revenue', 'revenue', 'total', 'quantity', 'owing', 'new', 'owed', 'type', 'status', 'products', 'amount', 'date', 'day', 'month', 'year', 'start_date', 'end_date'];

    public function scopeSupplierFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }
        try {
            $query = DB::table('suppliers');
            foreach ($filter as $key => $val) {
                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(id) as count'))
                            ->where('owed', '>', 0);
                    } elseif ($key == 'owed') {

                        if (!empty($val)) {
                            $val = explode('-', $val);
                            if ($val[0] == "l" || $val[0] == "L") {
                                $queryMade = $query
                                    ->select(DB::raw('SUM(owed) as owed , COUNT(id) as count'))
                                    ->where($key, '<', $val[1]);
                            } elseif ($val[0] == "m" || $val[0] == "M") {
                                $queryMade = $query
                                    ->select(DB::raw('SUM(owed) as owed, COUNT(id) as count'))
                                    ->where($key, '>', $val[1]);
                            }
                        } else {
                            $queryMade = $query
                                ->select(DB::raw('SUM(owed) as owed, COUNT(id) as count'));

                        }
                    } elseif ($key == 'amount') {
                        $queryMade = $query
                            ->select(DB::raw('id, name, owed'))
                            ->where('owed', '>', 0)
                            ->orderBy('owed', 'desc');

                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(id) as suppliers, ' . strtoupper($val) . '(suppliers.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(suppliers.created_at)'))
                            ->where('owed', '>', 0);
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'suppliers', $filter);
                    } else {
                        return $query;
                    }
                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }

    public function scopeCustomerFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }
        try {
            $query = DB::table('customers');
            foreach ($filter as $key => $val) {
                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(id) as count'))
                            ->where('owing', '>', 0);
                    } elseif ($key == 'owing') {

                        if (!empty($val)) {
                            $val = explode('-', $val);
                            if ($val[0] == "l" || $val[0] == "L") {
                                $queryMade = $query
                                    ->select(DB::raw('SUM(owing) as owing , COUNT(id) as count'))
                                    ->where($key, '<', $val[1]);
                            } elseif ($val[0] == "m" || $val[0] == "M") {
                                $queryMade = $query
                                    ->select(DB::raw('SUM(owing) as owing, COUNT(id) as count'))
                                    ->where($key, '>', $val[1]);
                            }
                        } else {
                            $queryMade = $query
                                ->select(DB::raw('SUM(owing) as owing, COUNT(id) as count'));

                        }

                    } elseif ($key == 'amount') {
                        $queryMade = $query
                            ->select(DB::raw('id, name, owing'))
                            ->where('owing', '>', 0)
                            ->orderBy('owing', 'desc');

                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(id) as customers, ' . strtoupper($val) . '(customers.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(customers.created_at)'))
                            ->where('owing', '>', 0);
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'customers', $filter);
                    } else {
                        return $query;
                    }
                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }

    
    public function scopeInvoiceFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }

        try {

            $query = DB::table('invoices');
            foreach ($filter as $key => $val) {
                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(id) as count'));
                    } elseif ($key == 'type') {
                        $queryMade = $query
                            ->select(DB::raw('SUM(amount) as amount, COUNT(id) as count'))
                            ->where($key, $val);
                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(id) as invoices, ' . strtoupper($val) . '(invoices.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(invoices.created_at)'));
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'invoices', $filter);
                    } else {
                        return $query;
                    }
                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }
    public function scopePurchaseFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }

        try {

            $query = DB::table('purchase_details')
                ->join('purchases', 'purchase_details.purchase_id', '=', 'purchases.id');
            foreach ($filter as $key => $val) {
                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(purchases.id) as count, SUM(quantity) as stock', 'SUM(price) as cost'));
                    } elseif ($key == 'products') {
                        $queryMade = $query
                            ->select(DB::raw('product, SUM(quantity) as quantity'))
                            ->groupBy('product', 'quantity')
                            ->orderBy('quantity', 'desc');
                    } elseif ($key == 'amount') {
                        $queryMade = $query
                            ->select(DB::raw('SUM(price * quantity) as amount'));
                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(product) as products, ' . strtoupper($val) . '(purchase_details.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(purchase_details.created_at)'));
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'purchase_details', $filter);
                    } else {
                        return $query;
                    }
                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }

    public function scopeOrderFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }
        try {
            $query = DB::table('order_details')
                ->join('orders', 'order_details.order_id', '=', 'orders.id');
            foreach ($filter as $key => $val) {
                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(orders.id) as count'));
                    } elseif ($key == 'products') {
                        $queryMade = $query
                            ->select(DB::raw('product, SUM(quantity) as quantity, SUM((100 - discount ) * (price/100)) as price'))
                            ->groupBy('product')
                            ->orderBy('quantity', 'desc');
                    } elseif ($key == 'amount') {
                        $queryMade = $query
                            ->select(DB::raw('SUM((100 - discount ) * (price/100) * quantity) as amount, SUM(cost_price * quantity) as cost, (SUM((100 - discount ) * (price/100) * quantity)) - (SUM(cost_price * quantity)) as profit'));
                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(product) as products, ' . strtoupper($val) . '(order_details.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(order_details.created_at)'));
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'order_details', $filter);
                    } else {
                        return $query;
                    }
                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }
    public function scopeProductFilter($queryx, $filter)
    {
        if (empty($filter['end_date'])) {
            $filter['end_date'] = Carbon::now()->toDateString();
        }
        try {
            $query = DB::table('attribute_product')
                ->join('products', 'attribute_product.product_id', '=', 'products.id');

            foreach ($filter as $key => $val) {

                if (in_array($key, $this->fields)) {
                    if ($key == 'count') {
                        $queryMade = $query->select(DB::raw('COUNT(attribute_product.id) as count,  SUM(available_stock) as stock'));
                    } elseif ($key == 'quantity') {
                        $queryMade = $query
                            ->select(DB::raw('products.name, SUM(attribute_product.available_stock) as quantity'))
                            ->groupBy('products.name', 'attribute_product.available_stock')
                            ->orderBy('attribute_product.available_stock', 'asc');
                    } elseif ($key == 'new') {
                        $queryMade = $query
                            ->select(DB::raw('attribute_product.id, products.name, attribute_product.available_stock'))
                            ->where('attribute_product.available_stock', '>', 0)
                            ->orderBy('attribute_product.created_at', 'desc');

                    } elseif ($key == 'total') {
                        $queryMade = $query->select(DB::raw('SUM(available_stock) as total'));
                    } elseif ($key == 'date') {
                        $queryMade = $query
                            ->select(DB::raw('COUNT(name) as products, ' . strtoupper($val) . '(attribute_product.created_at) as ' . strtolower($val)))
                            ->groupBy(DB::raw(strtoupper($val) . '(attribute_product.created_at)'));
                    }
                    //Date Filtering
                    if (!empty($queryMade)) {
                        return $this->dateFilter($queryMade, 'attribute_product', $filter);
                    } else {
                        return $query;
                    }

                }
            }
        } catch (\Exception $bug) {
            return $this->exception($bug);
        }
    }

    protected function datePartExpr(string $unit, string $qualifiedColumn): string
{
    $driver = DB::getDriverName();      // 'mysql', 'pgsql', ...
    $unit   = strtolower($unit);

    if (!in_array($unit, ['day','month','year'], true)) {
        throw new \InvalidArgumentException("Unsupported date unit: {$unit}");
    }

    // PostgreSQL -> EXTRACT(PART FROM column)
    if ($driver === 'pgsql') {
        return 'EXTRACT(' . strtoupper($unit) . " FROM {$qualifiedColumn})";
    }

    // MySQL/MariaDB -> PART(column)
    return strtoupper($unit) . "({$qualifiedColumn})";
}

public function scopeTransactionFilter($queryx, $filter)
{
    if (empty($filter['end_date'])) {
        $filter['end_date'] = Carbon::now()->toDateString();
    }

    try {
        $query = DB::table('transactions')
            ->join('invoices', 'transactions.invoice_id', '=', 'invoices.id');

        $queryMade = '';

        foreach ($filter as $key => $val) {
            if (in_array($key, $this->fields)) {
                if ($key == 'count') {
                    $queryMade = $query->select(DB::raw('COUNT(transactions.id) as count'));
                }

                if ($key == 'type') {
                    $queryMade = $query
                        ->select(DB::raw(
                            'SUM(transactions.payment) as balance, ' .
                            'SUM(transactions.amount)  as amount, '  .
                            'COUNT(transactions.id)    as "' . $val . '"'
                        ))
                        ->where('invoices.' . $key, $val);
                }

                if ($key == 'revenue') {
                    $queryMade = $query
                        ->select(DB::raw(
                            'SUM(transactions.payment)                     as revenue, ' .
                            'SUM(transactions.amount)                      as cost, '    .
                            'COUNT(transactions.id)                        as count, '   .
                            'SUM(transactions.payment - transactions.cost) as profit'
                        ));
                }

                if ($key == 'order_revenue') {
                    // safer and DB-agnostic vs the previous whereRaw with double quotes
                    $queryMade = $query
                        ->select(DB::raw(
                            'SUM(transactions.payment)                     as amount, ' .
                            'SUM(transactions.cost)                        as cost, '   .
                            'COUNT(transactions.id)                        as count, '  .
                            'SUM(transactions.payment - transactions.cost) as profit'
                        ))
                        ->where('transactions.payment', '>', 0)
                        ->where('invoices.type', 'order');
                }

                if ($key == 'status') {
                    $queryMade = $query
                        ->select(DB::raw(
                            'SUM(transactions.amount)  as amount, '  .
                            'COUNT(transactions.id)    as count, '   .
                            'SUM(transactions.payment) as balance'
                        ))
                        ->where('transactions.' . $key, $val);
                }

                if ($key == 'date' && !empty($filter['transactionType'])) {
                    // Build full, DB-agnostic date-part expression (no extra () appended later)
                    $dateExpr = $this->datePartExpr($val, 'transactions.created_at');
                    $alias    = strtolower($val);

                    $queryMade = $query
                        ->select(DB::raw(
                            'SUM(transactions.payment) as amount, '   .
                            'COUNT(transactions.id)    as transactions, ' .
                            $dateExpr . '             as ' . $alias
                        ))
                        ->where('invoices.type', $filter['transactionType'])
                        ->groupBy(DB::raw($dateExpr));
                }

                // keep your original “first match returns” behavior
                if (!empty($queryMade)) {
                    return $this->dateFilter($queryMade, 'transactions', $filter);
                } else {
                    return $query;
                }
            }
        }
    } catch (\Exception $bug) {
        return $this->exception($bug);
    }
}

public function dateFilter($queryMade, $table, $filter)
{
    $col    = $table . '.created_at';
    $driver = DB::getDriverName();

    if (!empty($filter['day'])) {
        $expr = ($driver === 'pgsql')
            ? 'EXTRACT(DAY FROM ' . $col . ')'
            : 'DAY(' . $col . ')';
        $queryMade = $queryMade->whereRaw($expr . ' = ?', [$filter['day']]);
    }

    if (!empty($filter['month'])) {
        $expr = ($driver === 'pgsql')
            ? 'EXTRACT(MONTH FROM ' . $col . ')'
            : 'MONTH(' . $col . ')';
        $queryMade = $queryMade->whereRaw($expr . ' = ?', [$filter['month']]);
    }

    if (!empty($filter['year'])) {
        $expr = ($driver === 'pgsql')
            ? 'EXTRACT(YEAR FROM ' . $col . ')'
            : 'YEAR(' . $col . ')';
        $queryMade = $queryMade->whereRaw($expr . ' = ?', [$filter['year']]);
    }

    if (!empty($filter['start_date'])) {
        // DB-agnostic and safe
        $start = $filter['start_date'];
        $end   = $filter['end_date'] ?? $start;
        $queryMade = $queryMade->whereBetween($col, [$start, $end]);
    }

    return $queryMade;
}
}
