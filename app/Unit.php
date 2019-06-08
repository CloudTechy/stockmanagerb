<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $primaryKey = "name";
    public $incrementing = false;
    protected $keyType = 'string';

    public function AttributeProducts()
    {

        return $this->hasMany(AttributeProduct::class);
    }
}
