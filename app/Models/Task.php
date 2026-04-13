<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Task extends Model
{use SoftDeletes;
    protected $fillable = [
        'project_id',
        'name',
        'assigned_to',
        'estimated_hours',
        'cost',
        'start_date',
        'end_date',
        'status'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}