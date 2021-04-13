<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Services\CarteiraService;
use App\Services\TransacaoService;
use Illuminate\Http\Request;

class TransacaoController extends Controller
{

    protected $transacaoService;

    public function __construct(TransacaoService $transacaoService)
    {
        $this->transacaoService = $transacaoService;
    }

    public function create(Request $request, CarteiraService $carteiraService)
    {
        $create = $this->transacaoService->create($request, $carteiraService);
        return response()->json($create["msg"], $create["statusCode"]);
    }
}
