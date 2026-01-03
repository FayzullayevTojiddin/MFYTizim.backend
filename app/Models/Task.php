<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_category_id',
        'neighborood_id',
        'description',
        'status',
        'file',
        'latitude',
        'longitude',
    ];

    public function category()
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    public function neighborood()
    {
        return $this->belongsTo(Neighborood::class, 'neighborood_id');
    }
}