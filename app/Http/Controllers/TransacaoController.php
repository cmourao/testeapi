<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Services\CarteiraService;
use App\Services\TransacaoService;
use Illuminate\Http\Request;
use App\Services\TransacaoPermissoesService;

class TransacaoController extends Controller
{

    protected $transacaoService;

    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function create(Request $request, CarteiraService $carteira, TransacaoPermissoesService $transacaoPermissoes)
    {
        $create = $this->transacaoService->create($request, $carteira, $transacaoPermissoes);
        return response()->json($create["msg"], $create["statusCode"]);
    }
}
