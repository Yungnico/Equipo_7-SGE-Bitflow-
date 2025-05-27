<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre_contacto',
        'email_contacto',
        'telefono_contacto',
        'tipo_contacto',
        'cliente_id',
    ];
    protected $table = 'contacto';

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
