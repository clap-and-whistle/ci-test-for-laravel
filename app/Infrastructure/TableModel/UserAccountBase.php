<?php

namespace App\Infrastructure\TableModel;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @method static self find($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($string, $string)
 * @property $id
 * @property $account_status
 * @property $email
 * @property $password
 */
class UserAccountBase extends Authenticatable
{
    protected $table = 'user_account_base';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

}
