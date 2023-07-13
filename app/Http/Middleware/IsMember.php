<?php

namespace App\Http\Middleware;

use App\Models\Membership;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMember
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $partyId = $request['partyId'];
        $userId = auth()->user()->id;

        $membership = Membership::where([
            ['user_id', $userId],
            ['party_id', $partyId],
        ])->first();

        if (!$membership) {
            return response()->json([
                'success' => false,
                'message' => 'You have not permissions to perform this action'
            ]);
        }

        return $next($request);
    }
}
