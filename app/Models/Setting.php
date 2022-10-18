<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $app_name
 * @property string $app_favicon
 * @property string $app_logo
 * @property string $created_at
 * @property string $shop_comission
 * @property string $updated_at
 * @property string $telefono
 * @property string $email
 * @property string $address
 * @property string $description
 */
class Setting extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['app_name', 'app_favicon', 'app_logo', 'created_at', 'shop_comission', 'updated_at', 'telefono', 'email', 'address', 'description'];
}
