<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Supplier extends Model
{
    protected $fillable = ['name', 'address', 'city','staff', 'country', 'number', 'email', 'is_stock_available', 'owed', 'due_date', 'user_id', "updated_by"];
    protected $appends = array('due');

    public function getDueAttribute()
    {
        $due_date = $this->due_date;
        if (($this->payment < $this->amount) && !empty($this->due_date)) {
            $due_date = Carbon::parse($this->due_date);

            if (Carbon::now() > $due_date) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['name', 'address', 'city', 'number', 'country', "email", "user_id", 'is_stock_available', "owed", "dateBefore", "dateAfter"];

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            if ($key == 'dateBefore') {
                                $val = Carbon::parse($val);
                                $query->where("created_at", "<=", $val);
                                continue;
                            } elseif ($key == 'dateAfter') {
                                $val = Carbon::parse($val);
                                $query->where("created_at", ">=", $val);
                                continue;
                            }

                            $query->where($key, $val);

                        }
                    }
                    return $query;

                });

        } catch (\Exception $bug) {

            return $this->exception($bug);
        }

    }

    public function products()
    {

        return $this->hasMany(Product::class);

    }
    public function user()
    {

        return $this->belongsTo(User::class);

    }
    public function purchases()
    {

        return $this->hasMany(Purchase::class);

    }

    public function bankDetails()
    {

        return $this->hasMany(BankDetail::class);

    }

}
