<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuponAgregarModel extends Model
{
    protected $table = 'cupones';
    protected $primaryKey = 'ID_Cupon';
    public $timestamps = false;
    protected $fillable = [
        'ID_Empresa',
        'Titulo',
        'Imagen',
        'PrecioR',
        'PrecioO',
        'Fecha_Inicial',
        'Fecha_Final',
        'Fecha_Limite',
        'Descripcion',
        'Stock',
        'Cantidad_Vendidos'
    ];

}
