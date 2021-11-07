<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'type'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that should be concat user first name and last name.
     *
     * @var array
     */
    protected $appends = ['full_name'];

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Many-To-Many Relationship Method for accessing the User->roles
     */

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    /**
     * Owner of the actual gate facades
     *
     */
//    public function owns($related)
//    {
//        return $this->id == $related->user_id;
//    }

    /**
     * Assign Roles to users
     */
    public function assignRole($role)
    {
        return  $this->roles()->syncWithoutDetaching(
            Role::whereName($role)->firstOrFail()
        );
    }
    /**
     * Assign multiple Roles to users
     */

    public function assignRoles($roles)
    {
        foreach ($roles as $role){
            $this->roles()->syncWithoutDetaching(
                Role::whereName($role)->firstOrFail()
            );
        }
        return $this;
    }

    /**
     * Check user has role
     *
     * @param  string  $role
     * @return string
     */
    public function hasRole($role)
    {
        if(is_string($role)){
            return $this->roles->contains('name', strtolower($role));
        }

        return !! $role->intersect($this->roles)->count();

        //array_filter except  intersection method

//        foreach ($role as $r){
//            if($this->hasRole($r->name)){
//                return true;
//            }
//        }
//        return false;
    }

    // Problem When deleting
//    public function getAvatarAttribute($avatar)
//    {
//        return getImage(imagePath()['profile']['path'] . '/' . $avatar, imagePath()['profile']['size']);
//    }

    /**
     * Check user is normal user or admin
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsAdmin($query)
    {
        return $query->where('is_admin', 1);
    }

     /**
     * Check user is active or not
     *
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function createdRoles()
    {
        return $this->hasMany(Role::class, 'created_by', 'id');
    }

    /**
     * Relationship about division with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Relationship about district with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district()
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Relationship about upazila with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    /**
     * Relationship about product price alert with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function priceAlerts()
    {
        return $this->hasMany(PriceAlert::class);
    }


}
