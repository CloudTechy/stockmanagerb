<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $primaryKey = "name";
    public $incrementing = false;
    protected $keyType = 'string';

    public function bankDetails()
    {

        return $this->hasMany(BankDetail::class);
    }
}
