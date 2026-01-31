<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FatherSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'father_id',
        'day',
        'from',
        'to',
        'slot_duration',
        'created_by',
    ];

    protected $casts = [
        'from' => 'datetime:H:i',
        'to' => 'datetime:H:i',
    ];

    public function father()
    {
        return $this->belongsTo(User::class, 'father_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getSlots()
    {
        $slots = [];
        $from = \Carbon\Carbon::parse($this->from);
        $to = \Carbon\Carbon::parse($this->to);

        while ($from < $to) {
            $slotEnd = $from->copy()->addMinutes($this->slot_duration);
            if ($slotEnd <= $to) {
                $slots[] = [
                    'from' => $from->format('H:i'),
                    'to' => $slotEnd->format('H:i'),
                ];
            }
            $from = $slotEnd;
        }

        return $slots;
    }
}
