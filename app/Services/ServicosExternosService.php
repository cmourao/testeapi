<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ServicosExternosService
{

    public static function enviarNotificacao()
    {
        $response = Http::get('https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04x');

        if($response->body() == "Not found"){
            return false;
        }

        $body = json_decode($response->body(), true);
        return $body["message"] == "Enviado" ? true : false;
    }

    public static function verificarServicoAutorizadorExterno()
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

        if($response->body() == "Not found"){
            return false;
        }

        $body = json_decode($response->body(), true);
        return $body["message"] == "Autorizado" ? true : false;
    }
}
