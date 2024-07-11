<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseStockMovement extends Model
{
    protected $fillable = [
        'id',
        'warehouse_id',
        'cabinet_id',
        'material_id',
        'project_id',
        'quantity',
        'top',
        'left'
    ];
}
