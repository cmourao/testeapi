<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaTipo extends Model
{

    use HasFactory;

    protected $table = 'pessoa_tipo';
    public $timestamps = false;
    protected $primaryKey = 'id';

    public function pesssoas()
    {
        return $this->hasMany('App\Models\Pessoa', 'pessoa_tipo_id', 'id');
    }
}
