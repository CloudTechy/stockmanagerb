<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = ['user_id', 'topic', 'message', 'date_start', 'date_end'];
    protected $appends = array('active');

    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function getActiveAttribute()
    {

        return Carbon::parse($this->date_end)->gt(Carbon::now());
    }

    public function scopeActive($query)
    {

        return $query->where('date_end', '>', Carbon::now());
    }

    public function scopeFilter($query, $filter)
    {

        try {

            $fields = ['active', 'auto_publish', 'topic', 'message', 'published'];
            return $query->where(
                function ($query) use ($filter, $fields) {
                    foreach ($filter as $key => $val) {
                        if (in_array($key, $fields)) {

                            $val = $val === 'false' ? 0 : $val;
                            $val = $val === 'true' ? 1 : $val;

                            if ($key == 'active' and $val == 1) {
                                $query->where('date_end', '>', Carbon::now());
                                continue;
                            } elseif ($key == 'active' and $val == 0) {
                                $query->where('date_end', '<', Carbon::now());
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
