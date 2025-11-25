<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RecommendationService;

class RecommendationController extends Controller
{
    protected $recommendationService;

    // Inject RecommendationService
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    /**
     * Mendapatkan rekomendasi berdasarkan player_id
     */
    public function next(Request $request)
    {
        $playerId = $request->query('player_id');
        if (!$playerId) return response()->json(['error' => 'player_id required'], 400);

        $result = $this->recommendationService->recommendNextQuestion($playerId);
        
        return response()->json($result);
    }

    /**
     * Mendapatkan jalur rekomendasi berdasarkan player_id
     */
    public function path(Request $request)
    {
        $playerId = $request->query('player_id');
        if (!$playerId) return response()->json(['error' => 'player_id required'], 400);

        $result = $this->recommendationService->getRecommendationPath($playerId);

        if (!$result) return response()->json(['error' => 'Profile not found'], 404);
        
        return response()->json($result);
    }

    /**
     * Mendapatkan perbandingan peer berdasarkan player_id
     */
    public function peer(Request $request)
    {
        $playerId = $request->query('player_id');

        if (!$playerId) {
            return response()->json(['error' => 'player_id is required'], 400);
        }

        try {
            $result = $this->recommendationService->getPeerComparison($playerId);

            if (!$result) {
                return response()->json([
                    'error' => 'Player profile or scores not found',
                    'player_id' => $playerId
                ], 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
