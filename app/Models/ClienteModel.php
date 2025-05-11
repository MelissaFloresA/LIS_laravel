<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClienteModel extends Model
{
    protected $table = 'cliente';
    protected $primaryKey = 'ID_Cliente';

    protected $fillable = [
        'Nombre',
        'Apellido',
        'Dui',
        'Telefono',
        'Direccion',
        'Correo',
        'Contrasena',
        'Token',
        'Estado'
    ];

    //relacion con tabla ventas (un cliente puede tener muchas ventas)
    public function ventas(): HasMany
    {
        return $this->hasMany(VentaModel::class, 'ID_Cliente');
    }

    //nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return $this->Nombre . ' ' . $this->Apellido;
    }
}