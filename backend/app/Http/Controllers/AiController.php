<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function suggestTasks(Request $request) { 
        $request->validate([
            'project_description' => 'required|string'
        ]);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type'  => 'application/json',
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            'model'    => 'llama-3.3-70b-versatile', // free and fast
            'messages' => [
                [
                    'role'    => 'user',
                    'content' => 'Break down this project into 5 actionable tasks: '
                                . $request->project_description
                ]
            ]
        ]);

        $data = $response->json();

        // return response()->json([
        //     'suggestions' => $data['choices'][0]['message']['content']
        // ]);

        return response()->json($data);
    }
}
