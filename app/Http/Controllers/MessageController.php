<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function create(Request $req, $partyId)
    {
        try {
            $userId = auth()->user()->id;
            $validator = Validator::make($req->all(), [
                'content' => ['required', 'string', 'min:10'],
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $validData = $validator->validated();

            $newMessage = Message::create([
                'content' => $validData['content'],
                'party_id' => intval($partyId),
                'user_id' => $userId,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'message' => $newMessage,
                ]
            ], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            Log::error('Error while register user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
