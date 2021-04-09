<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    use HasFactory;

    protected $table = 'carteira';
    public $timestamps = false;
    protected $fillable = ["saldo_atual", "saldo_transicao"];
}
