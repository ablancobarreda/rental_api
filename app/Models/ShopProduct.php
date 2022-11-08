<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $shop_id
 * @property string $name
 * @property integer $stock
 * @property integer $quantity_min
 * @property string $created_at
 * @property string $updated_at
 * @property string $slug
 * @property string $status
 * @property string $description
 * @property boolean $discount_status
 * @property float $discount_value
 * @property integer $rating
 * @property OrderProduct[] $orderProducts
 * @property User[] $users
 * @property ShopProductsPricesrate[] $shopProductsPricesrates
 * @property ShopProductPhoto[] $shopProductPhotos
 * @property ShopProductsHasCategoriesProduct[] $shopProductsHasCategoriesProducts
 * @property Shop $shop
 */
class ShopProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['shop_id', 'name', 'stock', 'quantity_min', 'created_at', 'updated_at', 'slug', 'status', 'description', 'discount_status', 'discount_value', 'rating'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'user_favorites_has_shop_products');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProductsPricesrates()
    {
        return $this->hasMany('App\Models\ShopProductsPricesrate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProductPhotos()
    {
        return $this->hasMany('App\Models\ShopProductPhoto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProductsHasCategoriesProducts()
    {
        return $this->hasMany('App\Models\ShopProductsHasCategoriesProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }
}
