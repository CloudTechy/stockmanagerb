<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'account_name', 'account_number', 'bank', 'supplier_id',
    ];
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['account_name', 'account_number', 'bank', 'supplier_id'];

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
