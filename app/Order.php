<?php

namespace App;

use App\Invoice;
use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;

class Order extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['customer_id','staff', 'customer_name', 'user_id', 'comment'];
    protected $appends = array('amount', 'quantity', 'cost', 'invoiceId', 'transactionId');

    public function getAmountAttribute()
    {
        return $this->orderDetails->sum('amount');
    }
    public function getInvoiceIdAttribute()
    {
        return empty($this->invoice) ? null : $this->invoice->id;
    }
    public function getTransactionIdAttribute()
    {
        return empty($this->transaction) ? null : $this->transaction->id;
    }

    public function getCostAttribute()
    {
        return $this->orderDetails->sum('cost');
    }
    public function getQuantityAttribute()
    {
        return $this->orderDetails->sum('quantity');
    }

    public function customer()
    {

        return $this->belongsTo(Customer::class);

    }
    public function user()
    {

        return $this->belongsTo(User::class);

    }
    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['customer_id', 'staff', 'user_id', 'updated_by'];

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            $query->where($key, $val);

                        }
                    }

                    return $query;

                });

        } catch (\Exception $bug) {

            return $this->exception($bug);
        }

    }

    public function orderDetails()
    {

        return $this->hasMany(OrderDetail::class);

    }

    public function invoice()
    {

        return $this->hasOne(Invoice::class);

    }
}
