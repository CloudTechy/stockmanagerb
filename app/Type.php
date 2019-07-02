<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    public $timestamps = false;
    protected $fillable = ['name'];
    protected $primaryKey = "name";
    public $incrementing = false;
    protected $keyType = 'string';

    public function invoice()
    {

        return $this->hasMany(Invoice::class);

    }
}
