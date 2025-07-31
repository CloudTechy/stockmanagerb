<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public $timestamps = false;
    protected $fillable = ['type'];
    protected $primaryKey = "type";
    public $incrementing = false;
    protected $keyType = 'string';

    public function transactions()
    {

        return $this->hasMany(Transaction::class);

    }
}
