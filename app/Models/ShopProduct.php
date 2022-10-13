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
 * @property User[] $users
 * @property ShopProductPhoto[] $shopProductPhotos
 * @property Shop $shop
 * @property OrderProduct[] $orderProducts
 * @property ShopProductsPricesrate[] $shopProductsPricesrates
 * @property CategoriesProduct[] $categoriesProducts
 */
class ShopProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['shop_id', 'name', 'stock', 'quantity_min', 'created_at', 'updated_at'];

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
    public function shopProductPhotos()
    {
        return $this->hasMany('App\Models\ShopProductPhoto');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo('App\Models\Shop');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany('App\Models\OrderProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProductsPricesrates()
    {
        return $this->hasMany('App\Models\ShopProductsPricesrate');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categoriesProducts()
    {
        return $this->belongsToMany('App\Models\CategoriesProduct', 'shop_products_has_categories_products', null, 'category_product_id');
    }
}
