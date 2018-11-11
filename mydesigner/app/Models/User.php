<?php

namespace MyDesigner\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'user_status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function roles()
	{
	  return $this->belongsToMany(Role::class);
	}

	public function teams()
	{
	  return $this->belongsToMany(Team::class);
	}

	public function styles()
	{
	  return $this->belongsToMany(Style::class);
	}

	public function designs()
	{
	  return $this->belongsToMany(Design::class)->withPivot('type');
	}

	public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }
	
	/**
	* @param string|array $roles
	*/
	public function authorizeRoles($roles)
	{
	  if (is_array($roles)) {
		  return $this->hasAnyRole($roles) || 
				 abort(401, 'This action is unauthorized.');
	  }
	  return $this->hasRole($roles) || 
			 abort(401, 'This action is unauthorized.');
	}
	
	/**
	* Check multiple roles
	* @param array $roles
	*/
	public function hasAnyRole($roles)
	{
	  return null !== $this->roles()->whereIn('role_name', $roles)->first();
	}
	
	/**
	* Check one role
	* @param string $role
	*/
	public function hasRole($role)
	{
	  return null !== $this->roles()->where('role_name', $role)->first();
	}
}
