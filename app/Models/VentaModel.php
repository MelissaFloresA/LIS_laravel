<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaModel extends Model
{
    protected $table = 'ventas';
    protected $primaryKey = 'ID_Venta';

    protected $fillable = [
        'ID_Cupon',
        'ID_Cliente',
        'Fecha_Compra',
        'Cantidad',
        'Veces_Canje',
        'Monto',
        'Metodo_Pago',
        'Estado',
        'Codigo_Cupon'
    ];

    //relaciones de venta por llave foranea
    public function cupon(): BelongsTo
    {
        return $this->belongsTo(CuponesModel::class, 'ID_Cupon');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(ClienteModel::class, 'ID_Cliente');
    }
}