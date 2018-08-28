<?php

namespace App\Http\Controllers\Functional;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CallController
{
    public function getCall(Request $request)
    {
        Log::info(serialize($request->all()));
    }
}