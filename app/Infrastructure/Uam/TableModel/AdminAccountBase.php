<?php

namespace App\Infrastructure\Uam\TableModel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Bizlogics\Uam\UseCase\AuthenticatableInterface as BizLogicsAuth;

/**
 * @method static self|null find($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($string, $string)
 * @property $id
 * @property $account_status
 * @property $email
 * @property $password
 */
class AdminAccountBase extends Authenticatable implements BizLogicsAuth
{
    use HasFactory;

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
