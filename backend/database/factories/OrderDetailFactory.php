<?php

use App\AttributeProduct;
use App\Product;
use Faker\Generator as Faker;

$factory->define(App\OrderDetail::class, function (Faker $faker) {

    $product = Product::inRandomOrder()->get()->first();
    $productVariations = $product->attributes->count();

    $productVariationsId = $product->variations->pluck('id');

    $variationId = $faker->randomElement($productVariationsId);

    $selectedProductVariation = AttributeProduct::find($variationId);

    return [
        'product' => $selectedProductVariation->product->name,
        'brand' => $selectedProductVariation->attribute->type,
        'quantity' => $faker->numberBetween(1, 20),
        'size' => $selectedProductVariation->size,
        'pku' => $selectedProductVariation->product->pku,
        'price' => $selectedProductVariation->price,
        'cost_price' => $selectedProductVariation->purchase_price,
        'category' => $selectedProductVariation->product->category,
        'discount' => $selectedProductVariation->product->discount,

    ];
});
