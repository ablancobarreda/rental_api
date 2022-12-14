<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $app_name
 * @property string $app_favicon
 * @property string $app_logo
 * @property string $created_at
 * @property float $shop_comission
 * @property string $updated_at
 * @property string $phone
 * @property string $email
 * @property string $address
 * @property string $description
 */
class Setting extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['app_name', 'app_favicon', 'app_logo', 'created_at', 'shop_comission', 'updated_at', 'phone', 'email', 'address', 'description'];
}
