<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_category_id',
        'worker_id',
        'deadline_at',
        'completed_at',
        'count',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'deadline_at' => 'datetime',
            'completed_at' => 'datetime',
            'status' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class);
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function isOverdue(): bool
    {
        return !$this->isCompleted() && $this->deadline_at->isPast();
    }

    public function myTasks(): HasMany
    {
        return $this->hasMany(MyTask::class);
    }
}