<?php
namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;
use Illuminate\Support\Facades\Hash;

/**
 * Class Admin
 * @package App\Models
 */
class Admin extends \Eloquent implements Authenticatable
{

    use AuthenticableTrait;
     
    /**
     * The database table used by the model.
     *
     * @var string
     */


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    protected $fillable = ['name', 'email', 'role_user', 'level', 'password','last_login'];
    protected $casts = [
        'last_login' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
