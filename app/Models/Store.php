<?php

namespace App\Models;

use App\Category;
use App\Notifications\MailResetStorePasswordNotification;
use App\Product;
use App\StoreSetting;
use Illuminate\Foundation\Auth\User as Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

/**
 *
 */
class Store extends Model implements JWTSubject
{
    use Notifiable;

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetStorePasswordNotification($token));
    }

    /**
     * @var string[]
     */
    protected $guarded = ['id'];

    /**
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @return mixed|string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * @param $id
     * @return mixed
     */
    public static function getStoreByViewId($id)
    {
        return Store::all()->where('view_id', $id)->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getSettings()
    {
        return $this->hasOne(StoreSetting::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getCoupons()
    {
        return $this->hasMany(Coupon::class);
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tables()
    {
        return $this->hasMany(Table::class, 'store_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class, 'store_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class, 'store_id', 'id');
    }
}
