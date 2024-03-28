<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $fillable = [
        'nome',
        'quantidade',
        'descricao',
        'uso_recomendado',
        'ficha_tecnica',
    ];
    
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'material_projects');
    }

    public function purchaseMaterials()
    {
        return $this->hasMany(PurchaseMaterial::class);
    }

    public function materialLocations()
    {
        return $this->hasMany(MaterialLocation::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class, 'shelf_id', 'id');
    }

}
