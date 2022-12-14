<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $cover
 * @property string $avatar
 * @property string $address
 * @property string $phone
 * @property string $email
 * @property string $facebook_link
 * @property string $instagram_link
 * @property string $twitter_link
 * @property string $wa_link
 * @property string $telegram_link
 * @property string $created_at
 * @property string $updated_at
 * @property string $url
 * @property boolean $delivery
 * @property boolean $pick
 * @property integer $user_id
 * @property string $slug
 * @property float $comission
 * @property ShopCoupon[] $shopCoupons
 * @property Order[] $orders
 * @property ShopCurrency[] $shopCurrencies
 * @property ShopProduct[] $shopProducts
 * @property ShopDeliveryZone[] $shopDeliveryZones
 */
class Shop extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['delivery', 'pick', 'name', 'description', 'cover', 'avatar', 'address', 'phone', 'email', 'facebook_link', 'instagram_link', 'twitter_link', 'wa_link', 'telegram_link', 'created_at', 'updated_at', 'url', 'user_id', 'slug', 'comission'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopCoupons()
    {
        return $this->hasMany('App\Models\ShopCoupon');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopCurrencies()
    {
        return $this->hasMany('App\Models\ShopCurrency');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopProducts()
    {
        return $this->hasMany('App\Models\ShopProduct');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shopDeliveryZones()
    {
        return $this->hasMany('App\Models\ShopDeliveryZone');
    }
}
