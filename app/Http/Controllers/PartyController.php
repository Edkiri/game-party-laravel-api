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
                    'membership' => $newMembership,
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

    public function join($partyId)
    {
        try {
            $userId = auth()->user()->id;

            $party = Party::find($partyId);

            if (!$party) {
                return response()->json([
                    'success' => false,
                    'message' => "Party with id '" . $partyId . "' not found"
                ], Response::HTTP_NOT_FOUND);
            }

            $oldMembership = Membership::where([
                ['user_id', $userId],
                ['party_id', $partyId],
            ])->first();

            if ($oldMembership) {
                return response()->json([
                    'success' => false,
                    'message' => "User is already member of this party"
                ], Response::HTTP_BAD_REQUEST);
            }

            $newMembership = Membership::create([
                'user_id' => $userId,
                'party_id' => $party->id,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'party' => $party,
                    'membership' => $newMembership,
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
    public function exit($partyId)
    {
        try {
            $userId = auth()->user()->id;

            $membership = Membership::where([
                ['user_id', $userId],
                ['party_id', $partyId],
            ])->first();

            if (!$membership) {
                return response()->json([
                    'success' => false,
                    'message' => "You do not have permissions to perform this action"
                ], Response::HTTP_BAD_REQUEST);
            }

            if ($membership->is_owner) {
                return response()->json([
                    'success' => false,
                    'message' => "Owner user can not exit his party"
                ], Response::HTTP_BAD_REQUEST);
            }

            $membership->delete();

            return response()->json([
                'success' => true,
                'data' => []
            ], Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            Log::error('Error while register user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getMembers($partyId)
    {
        try {
            $members = Membership::where('party_id', $partyId)->with('user')->get();

            if (!$members) {
                return response()->json([
                    'success' => false,
                    'message' => "Party with id '" . $partyId . "' not found"
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'members' => $members,
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error while register user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function listParties(Request $req)
    {
        try {
            $gameId = $req->gameId;
            $gameTitle = $req->gameTitle;

            $parties = Party::query();

            if ($gameId) {
                $parties->where('game_id', $gameId);
            }
            if ($gameTitle) {
                $parties->whereHas('game', function ($query) use ($gameTitle) {
                    $query->where('title', 'like', '%' . $gameTitle . '%');
                });
            }

            $parties = $parties->with('game')->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'parties' => $parties,
                ]
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            Log::error('Error while register user' . $th->getMessage());

            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
