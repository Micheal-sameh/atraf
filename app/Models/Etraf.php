<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etraf extends Model
{
    use HasFactory;

    protected $table = 'atraf';

    protected $fillable = [
        'father_id',
        'user_id',
        'date',
        'from',
        'to',
        'status',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'from' => 'datetime:H:i',
        'to' => 'datetime:H:i',
    ];

    public function father()
    {
        return $this->belongsTo(User::class, 'father_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getDayOfWeek()
    {
        return strtolower($this->date->format('l'));
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeWaiting($query)
    {
        return $query->where('status', 'waiting');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
