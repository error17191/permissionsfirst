<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasPermission($permissionId)
    {
        if ($this->is_super_admin) {
            return true;
        }
        $myPermissions = json_decode($this->permissions);
        return in_array($permissionId, $myPermissions);
    }

    public function canEditArticle($article)
    {
        if($this->hasPermission(Permissions::EDIT_ANY_POST)){
            return true;
        }
        if($this->hasPermission(Permissions::EDIT_MY_POST)){
            return $this->id == $article->user_id;
        }
    }
}
