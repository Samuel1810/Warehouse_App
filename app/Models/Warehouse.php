<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $primaryKey = 'id';
    public $incrementing = false;

    protected $fillable = ['id', 'layout', 'top', 'left'];

    public function shelves()
    {
        return $this->hasMany(Shelf::class);
    }

    public function materials()
    {
        return $this->hasManyThrough(Material::class, Shelf::class);
    }
}
