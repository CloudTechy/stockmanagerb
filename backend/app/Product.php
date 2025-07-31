<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;

class Product extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['name', 'supplier_id', 'description', 'image', 'pku', 'user_id', 'updated_by', 'discontinued', 'category', 'discount', 'discount_start', 'discount_end'];
    protected $appends = array('amountSale', 'variations', 'amountPurchase', 'stock', 'discountValidity');

    public function supplier()
    {

        return $this->belongsTo(Supplier::class);

    }

    public function user()
    {

        return $this->belongsTo(User::class);

    }
    public function getDiscountValidityAttribute()
    {
        if (!empty($this->discount)) {
            return Carbon::parse($this->discount_start)->lt(Carbon::now()) && Carbon::now()->lt(Carbon::parse($this->discount_end));

        } else {
            return false;
        }

    }

    public function category()
    {

        return $this->belongsTo(Category::class);

    }

    public function getStockAttribute()
    {
        $sum = 0;
        $attributes = $this->attributes()->get();

        foreach ($attributes as $attribute) {
            $sum += $attribute->attribute->available_stock;
        }
        return $sum;
    }
    public function getAmountSaleAttribute()
    {
        $sum = 0;
        $attributes = $this->attributes()->get();

        foreach ($attributes as $attribute) {
            $price = "";
            if (!empty($attribute->attribute->percent_sale)) {
                $price = (($attribute->attribute->percent_sale + 100) * $attribute->attribute->purchase_price) / 100;
                $sum += $price * $attribute->attribute->available_stock;

            } else {
                $price = $attribute->attribute->sale_price;
                $sum += $price * $attribute->attribute->available_stock;

            }

        }
        return $sum;

    }
    public function getAmountPurchaseAttribute()
    {
        $sum = 0;
        $attributes = $this->attributes()->get();

        foreach ($attributes as $attribute) {
            $sum += $attribute->attribute->purchase_price * $attribute->attribute->available_stock;
        }
        return $sum;
    }
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->withPivot('id', 'size', 'purchase_price', 'sale_price', 'percent_sale', 'available_stock')->as('attribute')->using('App\PrimaryPivot')->withTimestamps();

    }

    public function getVariationsAttribute($fields = " ")
    {
        return $this->attributes()->get()->pluck('attribute');

    }

    public function scopeFilter($query, $filter)
    {

        try {

            $fields = ['name', 'supplier_id', 'pku', 'category', 'discontinued', "user_id", "updated_by", "dateBefore", "dateAfter"];

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

}
