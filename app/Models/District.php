<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $guarded = [];

    /**
     * Relationship about division with district.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relationship about upazila with district.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function upazilas()
    {
        return $this->hasMany(Upazila::class, 'district_id');
    }

    /**
     * Relationship about user with district.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(User::class);
    }

    /**
     * fetch active district.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
