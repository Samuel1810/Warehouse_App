<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialProject extends Model
{
    protected $fillable = [
        'id',
        'material_id',
        'project_id'
    ];

    protected $table = 'materials_projects';

    public function materials()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function projects()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
