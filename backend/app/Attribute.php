<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $timestamps = false;
    protected $fillable = ['type', 'description'];

    public function products()
    {

        return $this->belongsToMany(Product::class);

    }
    public function attributes()
    {
        return $this->belongsToMany(Product::class)->withPivot('id', 'pku', 'size', 'purchase_price', 'sale_price', 'percent_sale', 'available_stock')->as('attribute')->using('App\PrimaryPivot')->withTimestamps();

    }
}
