<?php

namespace App\Infrastructure\Uam\TableModel;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Bizlogics\Uam\UseCase\UserOperation\Login\AuthenticatableInterface as BizLogicsAuth;

/**
 * @method static self find($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($string, $string)
 * @property $id
 * @property $account_status
 * @property $email
 * @property $password
 */
class UserAccountBase extends Authenticatable implements BizLogicsAuth
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

    public function getId(): int
    {
        return $this->id;
    }
}
