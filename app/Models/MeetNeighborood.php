<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetNeighborood extends Model
{
    protected $fillable = [
        'neighborood_id',
        'meet_id'
    ];

    public function neighborood()
    {
        return $this->belongsTo(Neighborood::class);
    }

    public function meet()
    {
        return $this->belongsTo(Meet::class);
    }
}
