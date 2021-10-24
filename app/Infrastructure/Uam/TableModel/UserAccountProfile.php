<?php

namespace App\Infrastructure\Uam\TableModel;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static self|null find($id)
 * @method static \Illuminate\Database\Eloquent\Builder where($string, $string)
 * @property $id
 * @property $user_account_base_id
 * @property $birth_date_str
 * @property $full_name
 * @property UserAccountBase $userAccountBase
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
        'id',
        'user_account_base_id',
        'birth_date_str',
        'full_name',
    ];

    public function userAccountBase(): BelongsTo
    {
        return $this->belongsTo(UserAccountBase::class);
    }
}
