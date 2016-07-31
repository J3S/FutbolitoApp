<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Hash;

class Usuario extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    
    protected $fillable = ['user', 'password'];

    // Override required, otherwise existing Authentication system will not match credentials
    public function getAuthPassword()
    {
        return $this->pass;
    }
    public function getPasswordAttribute() {
        return $this->pass;
    }
    public function setPasswordAttribute($pass) {
        $this->pass = Hash::make($pass);
    }
}
