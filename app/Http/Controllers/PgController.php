<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PgController extends Controller
{
    public function index()
    {
        return ["ok" => "teste"];
    }
}
