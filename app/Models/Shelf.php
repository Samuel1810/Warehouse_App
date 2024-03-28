<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Helpers\ModelMPK;

class Shelf extends Model
{
    public $incrementing = false;

    use HasFactory;

    protected $fillable = [
        'id'
    ];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function materials()
    {
        return $this->hasMany(Material::class);
    }

    protected function getKeyForSaveQuery()
    {
        $primaryKeyValues = [];
        
        foreach ((array) $this->getKeyName() as $key) {
            $primaryKeyValues[$key] = $this->getAttribute($key);
        }

        return $primaryKeyValues;
    }

}
