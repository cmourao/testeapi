<?php

namespace App\Http\Controllers;

use App\Models\PessoaTipo;
use Illuminate\Http\Request;

class PgController extends Controller
{
    public function index()
    {
        //return PessoaTipo::all()->toArray();
        return PessoaTipo::where('Descricao', 'Pessoa Física')->first();
    }

    public function transaction(Request $request)
    {
        $request = $request->all();

        //parametros faltantes ou transacao negativa
        if (
            !isset($request["value"]) ||
            !isset($request["payer"]) ||
            !isset($request["payee"]) ||
            $request["value"] < 0.01
        ) {
            return ["erro" => "transacao invalida"];
        }

        
        return [$request["value"]];
    }
}
