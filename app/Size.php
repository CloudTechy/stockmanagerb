<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $primaryKey = "name";
    public $timestamps = false;
    protected $fillable = ['name', 'description'];
    public $incrementing = false;
    protected $keyType = 'string';

    public function AttributeProducts()
    {

        return $this->hasMany(AttributeProduct::class);
    }
}
