<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property ShopProduct[] $shopProducts
 */
class CategoriesProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shopProducts()
    {
        return $this->belongsToMany('App\Models\ShopProduct', 'shop_products_has_categories_products', 'category_product_id');
    }
}
