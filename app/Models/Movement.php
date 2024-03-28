<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public function material()
    {
         return $this->belongsTo(Material::class);
    }

    protected $fillable = [
        'user_id', 
        'material_id', 
        'quantidade',
        'data_movimento',
        'tipo_movimento'
    ];

    protected $dates = ['data_movimento'];



}
