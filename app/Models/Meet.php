<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Meet extends Model
{
    protected $fillable = [
        'title',
        'description',
        'date_at'
    ];

    public function meetNeighboroods(): HasMany
    {
        return $this->hasMany(MeetNeighborood::class);
    }

    public function neighboroods(): BelongsToMany
    {
        return $this->belongsToMany(
            Neighborood::class,
            'meet_neighboroods',
            'meet_id',
            'neighborood_id'
        );
    }
}
