<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $slug
 * @property string $icon
 * @property integer $parent_id
 * @property ShopProductsHasCategoriesProduct[] $shopProductsHasCategoriesProducts
 * @property Promo[] $promos
 */
class CategoriesProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'created_at', 'updated_at', 'slug', 'parent_id', 'icon'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProductsHasCategoriesProducts()
    {
        return $this->hasMany('App\Models\ShopProductsHasCategoriesProduct', 'category_product_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function promos()
    {
        return $this->hasMany('App\Models\Promo', 'category_id');
    }
}
