<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaterialLocation extends Model
{
    protected $fillable = [
        'material_id',
        'warehouse_id',
        'shelf_id',
    ];

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function shelf()
    {
        return $this->belongsTo(Shelf::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
