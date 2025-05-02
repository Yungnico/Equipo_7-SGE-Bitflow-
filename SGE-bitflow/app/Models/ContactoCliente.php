<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContactoCliente extends Model
{
    use HasFactory;

    protected $table = 'contacto_clientes';

    protected $fillable = [
        'cliente_id',
        'nombre_contacto',
        'email_contacto',
        'telefono_contacto',
        'tipo_contacto',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
}
