<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Customer extends Model
{
    protected $fillable = ['name', 'number','staff', 'email', 'notes', 'owing', 'due_date', 'user_id', "updated_by"];
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

    public function orders()
    {

        return $this->hasMany(Order::class);

    }

    public function scopeFilter($query, $filter)
    {

        try {

            $fields = ['name', 'number', "email", "user_id", "owing", "dateBefore", "dateAfter"];

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

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    /*  public function scopeDateBefore($query, $date)
{

return $query->where("created_at", "<=", $date);
}
public function scopeDateAfter($query, $date)
{

return $query->where("created_at", ">=", $date);
}

public function scopeEmail($query, $email)
{

return $query->where("email", "=", $email);
}*/

}
