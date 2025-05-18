<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // 


class RepresentantesModel extends Model
{
    use Notifiable;
   protected $table = 'representantes';
    protected $primaryKey = 'ID_Representante';


    public $timestamps = false;
    //id incremental
    public $incrementing = true;

    protected $fillable = [
       'ID_Empresa',
       'Nombre',
       'Correo',
       'Contraseña',
       'Estado',
       
    ];

   // Define any relationships or additional methods here
   
    public function empresa()
    {
        return $this->belongsTo(EmpresaModel::class, 'ID_Empresa', 'ID_Empresa');
    }
    public function routeNotificationForMail($notification)
{
    return $this->Correo;
}



}
