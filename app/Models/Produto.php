<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    use HasFactory;
    protected $table = 'produtos';

    protected $fillable = ['nome', 'preco', 'is_deleted'];

    public function sales()
    {
        return $this->hasMany(Venda::class);
    }
}
