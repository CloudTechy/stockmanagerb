<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;

class Transaction extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
    protected $fillable = ['invoice_id', 'user_id', 'staff','payment_mode', 'updated_by', 'amount', 'cost', 'payment', 'status', 'due_date'];
    public $incrementing = false;
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

    public function status()
    {

        return $this->belongsTo(Status::class);

    }

    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function invoice()
    {

        return $this->belongsTo(Invoice::class);

    }

    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['invoice_id', 'user_id', 'updated_by', 'amount', 'payment', 'status', "due", 'dateBefore', "dateAfter"];

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            if ($key == 'due_date') {
                                $val = Carbon::now();
                                $query->where("due_date", ">", $val);
                                continue;
                            } elseif ($key == 'dateAfter') {
                                $val = Carbon::parse($val);
                                $query->where("created_at", ">=", $val);
                                continue;
                            } elseif ($key == 'dateBefore') {
                                $val = Carbon::parse($val);
                                $query->where("created_at", "<=", $val);
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
}
