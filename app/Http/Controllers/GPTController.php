<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GPTService;

class GPTController extends Controller
{
    private $gptService;

    public function __construct(GPTService $gptService)
    {
        $this->gptService = $gptService;

    }
    public function store(Request $request)
    {
       
    
        $result = $this->gptService->askAi($request->all());
        
        return response()->json(['result' => $result], 201);
    }
}
