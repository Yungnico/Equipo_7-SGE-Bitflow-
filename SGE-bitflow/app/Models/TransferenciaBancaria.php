<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferenciaBancaria extends Model
{
    protected $table = 'transferencias_bancarias';

    protected $fillable = [
        'fecha_transaccion',
        'hora_transaccion',
        'fecha_contable',
        'codigo_transferencia',
        'tipo_transaccion',
        'glosa_detalle',
        'ingreso',
        'egreso',
        'saldo_contable',
        'nombre',
        'rut',
        'numero_cuenta',
        'tipo_cuenta',
        'banco',
        'comentario_transferencia',
    ];
}
