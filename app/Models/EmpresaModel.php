<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class EmpresaModel extends Model
{
    use Notifiable;
     protected $table = 'empresa';
    protected $primaryKey = 'ID_Empresa';
     public $timestamps = false;
     protected $keyType = 'string';
       public $incrementing = false;

     protected $fillable = [
         'ID_Empresa',
       'Nombre',
       'Direccion',
       'Nombre_Contacto',
       'Telefono',
       'Correo',
       'ID_Rubro',
       'Porcentaje_Comision'
     ];

     //validacion de los campos

     public static function validacion($id = null)
     {
         return [
                'ID_Empresa' => [
                    'required',
                    
                    'exists:empresa,ID_Empresa'
                ],
             'Nombre' => [
                 'required',
                 'string',
                 'max:100',
                 'regex:/^[\pL\s]+$/u', 
                
             ],
             'Direccion' => [
                 'required',
                 'string',
                 'max:100',
             ],
             'Nombre_Contacto' => [
                 'required',
                 'string',
                 'max:100',
             ],
             'Telefono' => [
                 'required',
                 'string',
                 'max:15',
             ],
             'Correo' => [
                 'required',
                 'email',
             ],
             'ID_Rubro' => [
                 'required'
             ],
             'Porcentaje_Comision' => [
                 'required'
             ]
         ];
     }
public function rubro()
{
    return $this->belongsTo(RubrosModel::class, 'ID_Rubro', 'ID_Rubro');
}
public function cupones()
{
    return $this->hasMany(Cupones::class);
}
public function routeNotificationForMail()
{
    return $this->Correo;
}



}
