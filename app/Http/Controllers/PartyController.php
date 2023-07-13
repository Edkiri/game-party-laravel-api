<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PartyController extends Controller
{
    public function create(Request $req)
    {
        try {
            $userId = auth()->user()->id;

            $validator = Validator::make($req->all(), [
                'name' => ['required', 'string', 'min:4'],
                'game_id' => ['required', 'int'],
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            $validData = $validator->validated();

            $newParty = Party::create([
                'name' => $validData['name'],
                'game_id' => $validData['game_id'],
            ]);

            $newMembership = Membership::create([
                'user_id' => $userId,
                'party_id' => $newParty->id,
                'is_owner' => true,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'party' => $newParty,
                    'membsership' => $newMembership,
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
