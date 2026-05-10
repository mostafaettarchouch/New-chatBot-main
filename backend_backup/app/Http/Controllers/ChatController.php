<?php

namespace App\Http\Controllers;

use App\Services\ChatMatchingService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;

    public function __construct(ChatMatchingService $chatService)
    {
        $this->chatService = $chatService;
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $result = $this->chatService->processMessage($request->message);

        return response()->json($result);
    }
}