<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;
    protected $dateFormat = 'U';
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    const DELETED_AT = 'deletedAt';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code','status','discountValue','expiredAt'
    ];


    /**
     * scopeExist check  column have value or not
     *
     * @param  string $key
     *  @param  mixed $query
     * @return bool
     */
    public function scopeexist($query, $key,$value)
    {
        $res = $query->where($key,$value)->get();
        return !is_null($res) ? true : false;
    }
}
