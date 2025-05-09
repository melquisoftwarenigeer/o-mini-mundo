<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'project_id',
        'start_date',
        'end_date',
        'predecessor_id',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function predecessor()
    {
        return $this->belongsTo(Task::class, 'predecessor_id');
    }

    public function dependents()
    {
        return $this->hasMany(Task::class, 'predecessor_id');
    }
}
