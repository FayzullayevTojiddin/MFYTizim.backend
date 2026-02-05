<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'address',
        'location',
        'meet_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'location' => 'array',
            'meet_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function meetWorkers(): HasMany
    {
        return $this->hasMany(MeetWorker::class);
    }

    public function workers(): BelongsToMany
    {
        return $this->belongsToMany(Worker::class, 'meet_workers')
                    ->withPivot('status', 'seen_at', 'responded_at')
                    ->withTimestamps();
    }

    public function pendingWorkers(): BelongsToMany
    {
        return $this->workers()->wherePivot('status', 'pending');
    }

    public function acceptedWorkers(): BelongsToMany
    {
        return $this->workers()->wherePivot('status', 'accepted');
    }
}