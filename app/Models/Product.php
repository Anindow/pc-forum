<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $appends = ['review_count', 'avg_rating'];

    /**
     * Use slug instead of id field
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relationship about brand with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Relationship about category with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * Relationship about tag with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    /**
     * Relationship about product images with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    /**
     * Relationship about product link with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productLinks()
    {
        return $this->hasMany(ProductLink::class, 'product_id');
    }

    /**
     * Relationship about review with product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    /**
     * Check product is active or not
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     * Get total review count for product
     *
     * @return mixed
     */
    public function getReviewCountAttribute()
    {
        $count = $this->reviews->count();
        if($count){
            return $count;
        }else{
            return 0;
        }
    }

    /**
     * Get average rating count for product
     *
     * @return mixed
     */
    public function getAvgRatingAttribute()
    {
        $avgRating = $this->reviews->avg('rating');
        if($avgRating){
            return $avgRating;
        }
    }
}
