<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \BinaryCabin\LaravelUUID\Traits\HasUUID;
use Carbon\Carbon;

class AttributeProduct extends Model
{
    use HasUUID;
    protected $uuidFieldName = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable = ['product_id', 'attribute_id', 'size', 'purchase_price', 'user_id', 'updated_by', 'sale_price', 'percent_sale', 'available_stock'];
    protected $table = "attribute_product";
    protected $appends = array('price', 'amount');

    public function getAmountAttribute()
    {

        return $this->price * $this->available_stock;

    }

    public function scopeFilter($query, $filter)
    {

        try {

            $fields = ['product_id', 'attribute_id', 'size', 'purchase_price', 'sale_price', 'percent_sale', 'available_stock', 'user_id', 'updated_by', "brand", "dateBefore", "dateAfter"];

            return $query->where(
                function ($query) use ($filter, $fields) {

                    foreach ($filter as $key => $val) {

                        if (in_array($key, $fields)) {

                            if ($key == 'dateBefore') {
                                $val = Carbon::parse($val);
                                $query->where("updated_at", "<=", $val);
                                continue;
                            } elseif ($key == 'dateAfter') {
                                $val = Carbon::parse($val);
                                $query->where("updated_at", ">=", $val);
                                continue;
                            } elseif ($key == 'brand') {

                                $brand = Attribute::where('type', $val)->first();
                                if (!empty($brand)) {
                                    $query->where("attribute_id", $brand->id);

                                }
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
    public function unit()
    {

        return $this->belongsTo(Unit::class);

    }
    public function user()
    {

        return $this->belongsTo(User::class);

    }

    public function size()
    {

        return $this->belongsTo(Size::class);

    }
    public function product()
    {

        return $this->belongsTo(Product::class);

    }

    public function attribute()
    {

        return $this->belongsTo(Attribute::class);

    }
    public function getPriceAttribute()
    {
        $price = "";
        if (!empty($this->percent_sale)) {
            $price = (($this->percent_sale + 100) * $this->purchase_price) / 100;
            return $price;
        } else {
            $price = $this->sale_price;
            return $price;

        }

    }
}
