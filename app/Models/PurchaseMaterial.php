<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseMaterial extends Model
{
    protected $fillable = [
        'supplier_id',
        'manufacturer_id',
        'material_id',
        'quantity',
        'date',
        'user_id',
        'movement_type',
        'payment_proof',
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fornecedores()
    {
        return $this->belongsTo(Fornecedor::class, 'supplier_id');
    }

    public function manufacturers()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacturer_id');
    }
    

    public function getDataFormattedAttribute()
    {
        $dataAtual = \Carbon\Carbon::createFromFormat('Y-m-d', $this->attributes['date']);
        
        return $dataAtual->format('d/m/Y');
    }
}
