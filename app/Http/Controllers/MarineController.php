<?php

namespace App\Http\Controllers;

use App\Services\MarineService;

class MarineController extends Controller
{
    public function __construct(private MarineService $marine)
    {
    }

    public function index()
    {
        return view('marine.index');
    }

    public function current()
    {
        return response()->json($this->marine->ports());
    }
}
