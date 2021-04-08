<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransacaoPermissoes extends Model
{
    use HasFactory;

    protected $table = 'transacao_permissoes';
    public $timestamps = false;
}
