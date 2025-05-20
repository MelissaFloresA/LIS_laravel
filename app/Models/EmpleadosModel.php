<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmpleadosModel extends Model
{
    use Notifiable;

    protected $table = 'empleados';
    protected $primaryKey = 'ID_Empleado';
    public $timestamps = false;

    protected $fillable = [
        'ID_Empresa',
        'Nombre',
        'Apellido',
        'Correo',
        'Contrasena',
        'ID_Rol',
        'Estado'
    ];

    public function routeNotificationForMail($notification)
    {
        return $this->Correo;
    }
}
