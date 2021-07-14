<?php

namespace App\Infrastructure\Uam\TableModel;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static self find($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($string, $string)
 * @property $id
 * @property $birth_date_str
 * @property $full_name
 */
class UserAccountProfile extends Model
{
    protected $table = 'user_account_profile';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'birth_date_str',
        'full_name',
    ];

    public function userAccountBase()
    {
        return $this->hasOne(UserAccountBase::class, 'user_account_profile_id');
    }
}
