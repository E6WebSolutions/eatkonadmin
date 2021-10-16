<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Category extends Model
{
    /** @var array */
    protected $guarded = [];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productinfos()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productInfo()
    {
        return $this->hasMany(Product::class)->where('is_active', '=', 1)->orderBy('name');
    }

}
