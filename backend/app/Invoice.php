<?php

namespace App;

use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;

class Invoice extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
    protected $fillable = ['type',  'staff', 'purchase_id', 'balance', 'order_id', 'user_id', 'cost', 'amount'];
    public $incrementing = false;
    protected $appends = array('status','paymentmode');
    // protected $trx = Transaction::where('invoice_id', $this->id)->first();

    public function getStatusAttribute()
    {
        return empty(Transaction::where('invoice_id', $this->id)->first()) ? null : Transaction::where('invoice_id', $this->id)->first()->status;

    }
    public function getPaymentmodeAttribute()
    {
        return empty(Transaction::where('invoice_id', $this->id)->first()) ? null : Transaction::where('invoice_id', $this->id)->first()->payment_mode;

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
            $fields = ['type', 'purchase_id', 'order_id', 'cost', 'user_id', 'amount'];

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
}
