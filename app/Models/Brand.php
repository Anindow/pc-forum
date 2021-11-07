<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Use slug instead of id field
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function  products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Check brand is active or not
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
