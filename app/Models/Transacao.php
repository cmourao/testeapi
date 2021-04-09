<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transacao extends Model
{
    use HasFactory;

    protected $table = 'transacao';
    public $timestamps = false;
    protected $fillable = ['pessoa_origem_id', 'pessoa_destino_id', 'valor','transacao_estado_id'];

}
