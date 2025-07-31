<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['supplier_id','supplier_name','staff', 'user_id', 'comment'];
    protected $appends = array('amount', 'quantity', 'InvoiceId', 'TransactionId');

    public function getInvoiceIdAttribute()
    {
        return empty($this->invoice) ? null : $this->invoice->id;
    }
    public function getTransactionIdAttribute()
    {
        return empty($this->transaction) ? null : $this->transaction->id;
    }
    public function getAmountAttribute()
    {
        return $this->purchaseDetails->sum('amount');
    }
    public function getQuantityAttribute()
    {
        return $this->purchaseDetails->sum('quantity');
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function user()
    {

        return $this->belongsTo(User::class);

    }
    public function supplier()
    {

        return $this->belongsTo(Supplier::class);

    }

    public function invoice()
    {

        return $this->hasOne(Invoice::class);

    }
    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['supplier_id','staff', 'user_id', 'updated_by'];

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

        } catch (\Exception $bug) {

            return $this->exception($bug);
        }

    }

}
