<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransacaoEstado extends Model
{
    use HasFactory;
    
    protected $table = 'transacao_estado';
    public $timestamps = false;
}
