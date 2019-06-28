<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $fillable = ['purchase_id', 'category', 'product', 'brand', 'sale_price', 'quantity', 'price', 'percent_sale', 'pku', 'size'];
    protected $table = "purchase_details";
    protected $appends = array('amount');

    public function getAmountAttribute()
    {
        return $this->price * $this->quantity;

    }

    public function purchase()
    {

        return $this->belongsTo(Purchase::class);

    }

    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['purchase_id', 'product', 'category', 'brand', 'quantity', 'percent_sale', 'sale_price', 'price', 'pku', 'size'];

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            $query->where($key, $val);

                        }
                    }
                    // dd($query->toSql());
                    return $query;

                });

        } catch (Exception $bug) {

            return $this->exception($bug);
        }

    }

}
