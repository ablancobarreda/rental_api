<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $email
 * @property string $name
 * @property string $category
 * @property string $created_at
 * @property string $updated_at
 */
class Suscription extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['email', 'name', 'category', 'created_at', 'updated_at'];
}
