<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rule;

class RubrosModel extends Model
{
    use HasFactory;

    protected $table = 'rubros';
    protected $primaryKey = 'ID_Rubro';
    public $timestamps = false;

    protected $fillable = [
        'Nombre'
    ];

    // Validación  para el nombre del rubro 
    public static function rules($id = null)
    {
        return [
            'Nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\pL\s]+$/u', // Solo letras y espacios
                Rule::unique('rubros', 'Nombre')->ignore($id, 'ID_Rubro')
            ]
        ];
    }

    // Relación con empresas (pendiente)
    public function empresas(): HasMany
    {
        return $this->hasMany(EmpresaModel::class, 'ID_Rubro', 'ID_Rubro');
    }
}