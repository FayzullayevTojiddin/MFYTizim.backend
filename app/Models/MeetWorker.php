<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MeetWorker extends Model
{
    use HasFactory;

    protected $fillable = [
        'meet_id',
        'worker_id',
        'status',
        'seen_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'seen_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function meet(): BelongsTo
    {
        return $this->belongsTo(Meet::class);
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function isSeen(): bool
    {
        return $this->seen_at !== null;
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }
}