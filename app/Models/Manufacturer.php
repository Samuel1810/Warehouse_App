<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manufacturer extends Model
{
    use HasFactory;

    protected $table = 'manufacturers';

    protected $fillable = [
        'nome',
        'morada',
        'telefone',
        'email',
        'website',
    ];

    public function purchaseMaterials()
    {
        return $this->hasMany(PurchaseMaterial::class);
    }
}
