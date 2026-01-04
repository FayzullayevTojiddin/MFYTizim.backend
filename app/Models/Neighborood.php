<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Neighborood extends Model
{
    protected $fillable = [
        'title',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function meets(): BelongsToMany
    {
        return $this->belongsToMany(
            Meet::class,
            'meet_neighboroods',
            'neighborood_id',
            'meet_id'
        );
    }
}
