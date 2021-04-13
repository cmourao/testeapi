<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoa';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function carteira()
    {
        $this->hasOne('App\Models\Carteira', 'pessoa_id', 'id');
    }

    public function pessoaTipo()
    {
        $this->belongsTo('App\Models\PessoaTipo', 'id', 'pessoa_tipo_id');
    }
}
