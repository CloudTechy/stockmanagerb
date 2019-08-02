<?php

namespace App;

use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;

class Invoice extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
<<<<<<< HEAD
    protected $fillable = ['type', 'purchase_id', 'balance', 'order_id', 'user_id', 'cost', 'amount'];
=======
    protected $fillable = ['type', 'purchase_id', 'balance', 'order_id', 'user_id', 'amount'];
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b
    public $incrementing = false;
    protected $appends = array('status');

    public function getStatusAttribute()
    {
<<<<<<< HEAD
        return empty(Transaction::where('invoice_id', $this->id)->first()->status) ? null : Transaction::where('invoice_id', $this->id)->first()->status;
=======
        return Transaction::where('invoice_id', $this->id)->first()->status;
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

    }

    public function type()
    {

        return $this->belongsTo(Type::class);

    }
    public function user()
    {

        return $this->belongsTo(User::class);

    }
    public function transaction()
    {

        return $this->hasOne(Transaction::class);

    }
    public function purchase()
    {

        return $this->belongsTo(Purchase::class);

    }
    public function Order()
    {

        return $this->belongsTo(Order::class);

    }
    public function scopeFilter($query, $filter)
    {

        try {
<<<<<<< HEAD
            $fields = ['type', 'purchase_id', 'order_id', 'cost', 'user_id', 'amount'];
=======
            $fields = ['type', 'purchase_id', 'order_id', 'user_id', 'amount'];
>>>>>>> a90f05ca68e2264c685a9477281ef51e4d16983b

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            $query->where($key, $val);

                        }
                    }

                    return $query;

                });

        } catch (Exception $bug) {

            return $this->exception($bug);
        }

    }
}
