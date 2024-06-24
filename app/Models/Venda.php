<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    protected $table = 'vendas'; 

    protected $fillable = ['cliente_id', 'produto_id', 'quantidade', 'preco_unitario', 'preco_total', 'data_venda'];

    public function client()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function product()
    {
        return $this->belongsTo(Produto::class);
    }
}
