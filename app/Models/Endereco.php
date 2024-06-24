<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
    use HasFactory;
    protected $table = 'enderecos';

    protected $fillable = ['cliente_id', 'rua', 'cidade', 'estado', 'cep'];

    public function client()
    {
        return $this->belongsTo(Cliente::class);
    }
}
