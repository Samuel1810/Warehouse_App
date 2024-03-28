<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProjects extends Model
{
    protected $fillable = [
        'material_id',
        'project_id'
    ];

    protected $table = 'materials_projects';

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
