<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FatherUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'father_id',
        'user_id',
    ];

    public function father()
    {
        return $this->belongsTo(User::class, 'father_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
