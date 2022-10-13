<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property integer $municipality_id
 * @property string $created_at
 * @property string $updated_at
 * @property User $user
 * @property Municipality $municipality
 */
class UserAddress extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['user_id', 'municipality_id', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function municipality()
    {
        return $this->belongsTo('App\Models\Municipality');
    }
}
