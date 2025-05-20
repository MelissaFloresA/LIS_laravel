<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuponesModel extends Model
{
    protected $table = 'cupones';
    protected $primaryKey = 'ID_Cupon';
    public $timestamps = false; // Esto desactiva los campos created_at y updated_at

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
        'Cantidad_Vendidos',
        'Estado_Aprobacion',
        'Estado_Cupon',
        'Justificacion'
    ];

    //-----------PARA LISTAR LOS CUPONES PARA APROBAR----------//
    //manejo de estamos de cupones
    const ESTADOS_APROBACION = [
        'EN_ESPERA' => 'En espera',
        'ACTIVO' => 'Activa',
        'RECHAZADO' => 'Rechazado'
    ];

    const ESTADOS_CUPON = [
        'DISPONIBLE' => 'Disponible',
        'NO_DISPONIBLE' => 'No disponible'
    ];

    //funcion con consulta para listar cupones en espera de aprobación
    public function scopePendientes($query)
    {
        return $query->where('Estado_Aprobacion', 'En espera');
    }
}
