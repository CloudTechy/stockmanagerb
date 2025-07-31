<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    public $timestamps = false;
    protected $fillable = [];

    public function users()
    {

        return $this->hasMany(User::class);

    }
}
