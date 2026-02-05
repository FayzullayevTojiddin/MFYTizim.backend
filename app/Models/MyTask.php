<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MyTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'status',
        'description',
        'files',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'files' => 'array',
            'location' => 'array',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}