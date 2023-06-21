<?php

namespace App;


use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'last_name', 'activated', 'first_name', 'number', 'email', 'address', 'user_level_id', 'password',
    ];
    protected $appends = array('isAdmin', 'isSuperAdmin');

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function purchases()
    {

        return $this->hasMany(Purchase::class);

    }
    public function scopeFilter($query, $filter)
    {

        try {
            $fields = ['username', 'last_name', 'activated', 'first_name', 'number', 'email', 'address', 'user_level_id', 'password',
                'dateBefore', 'dateAfter'];

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

    public function orders()
    {

        return $this->hasMany(Order::class);

    }
    public function products()
    {

        return $this->hasMany(Product::class);

    }

    public function customers()
    {

        return $this->hasMany(Customer::class);

    }

    public function userLevel()
    {

        return $this->belongsTo(UserLevel::class);

    }

    public function announcements()
    {

        return $this->hasMany(Announcement::class);

    }
    public function getIsSuperAdminAttribute()
    {

        if ($this->userLevel->name == 'super-administrator') {
            return true;
        } else {
            return false;
        }

    }

    public function getIsAdminAttribute()
    {

        if ($this->userLevel->name == 'administrator' || $this->userLevel->name == 'super-administrator') {

            return true;
        } else {
            return false;
        }

    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims()
    {
        return [];
    }

}
