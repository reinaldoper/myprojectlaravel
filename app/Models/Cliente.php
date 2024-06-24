<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'cpf'];

    public function enderecos()
    {
        return $this->hasMany(Endereco::class);
    }

    public function telefones()
    {
        return $this->hasMany(Telefone::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}

