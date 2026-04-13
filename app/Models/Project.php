<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Task; // 🔥 THÊM DÒNG NÀY

class Project extends Model
{
    protected $fillable = [
        'project_code',
        'name',
        'description',
        'leader',
        'start_date',
        'end_date',
        'status'
    ];

    // 🔥 PROJECT - USER (many to many)
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // 🔥 PROJECT - TASK (1 project có nhiều task)
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}