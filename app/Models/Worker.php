<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Worker extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'phone_number'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function completedTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereNotNull('completed_at');
    }

    public function pendingTasks(): HasMany
    {
        return $this->hasMany(Task::class)->whereNull('completed_at');
    }

    public function locations(): HasMany
    {
        return $this->hasMany(WorkerLocation::class);
    }

    public function latestLocation(): HasOne
    {
        return $this->hasOne(WorkerLocation::class)->latestOfMany();
    }

    public function meets(): BelongsToMany
    {
        return $this->belongsToMany(Meet::class, 'meet_workers')
                    ->withPivot('status', 'seen_at', 'responded_at')
                    ->withTimestamps();
    }
}