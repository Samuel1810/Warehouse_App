<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProject extends Model
{
    protected $table = 'user_projects';

    protected $fillable = [
        'id',
        'user_id',
        'project_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
