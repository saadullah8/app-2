<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $fillable = [
        'cardId', 'userId', 'lastDigits', 'brand', 'brandImageURL','isDefault'
    ];
    public function getDateFormat()
    {
        return 'U';
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'userId');
    }
}
