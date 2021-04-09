<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransacaoRequest;
use Illuminate\Http\Request;
use App\Services\TransacaoService;
use App\Services\CarteiraService;
use Illuminate\Support\Facades\Validator;

class TransacaoController extends Controller
{

    protected $transacaoService, $carteiraService;

    public function __construct(TransacaoService $transacaoService, CarteiraService $carteiraService)
    {
        $this->transacaoService = $transacaoService;
        $this->carteiraService = $carteiraService;
    }

    public function create(Request $request)
    {

        $create = $this->transacaoService->create($request, $this->carteiraService);

        return response()->json($create["msg"], $create["statusCode"]);
    }
}
