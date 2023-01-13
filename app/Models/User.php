<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /*
        * Function name : setPasswordAttribute
        * Purpose       : To get hash password
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : $pass
        * Return Value  : Hashed value
    */
    public function setPasswordAttribute($pass) {
        $this->attributes['password'] = \Hash::make($pass);
    }

    /*
        * Function name : getFirstNameAttribute
        * Purpose       : To get capitalized value
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : $pass
        * Return Value  : Capitalized value
    */
    public function getFirstNameAttribute($firstName) {
        return ucfirst($firstName);
    }

    /*
        * Function name : role
        * Purpose       : To get roles
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : 
        * Return Value  :
    */
    public function role() {
        return $this->belongsTo('App\Models\Role', 'role_id');
    }

    /*
        * Function name : checkRolePermission
        * Purpose       : To get role permissions
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : 
        * Return Value  : 
    */
    public function checkRolePermission() {
        return $this->belongsTo('App\Models\Role', 'role_id')->where('is_admin','1');
    }

    /*
        * Function name : allRolePermissionForUser
        * Purpose       : To get all role permissions for a admin
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : 
        * Return Value  : 
    */
    public function allRolePermissionForUser() {
        return $this->hasMany('App\Models\RolePermission', 'role_id', 'role_id');
    }

    /*
        * Function name : userRoles
        * Purpose       : To get user roles
        * Author        :
        * Created Date  :
        * Modified Date : 
        * Input Params  : 
        * Return Value  : 
    */
    public function userRoles() {
        return $this->belongsToMany('App\Models\Role', 'App\Models\UserRole', 'user_id', 'role_id');
    }

}
