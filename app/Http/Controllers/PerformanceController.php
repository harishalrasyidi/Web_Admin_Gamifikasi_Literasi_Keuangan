<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PerformanceService;

class PerformanceController extends Controller
{
    protected $performanceService;
    // Kita inject PerformanceService karena data skor ada di PlayerProfile
    public function __construct(PerformanceService $performanceService)
    {
        $this->performanceService = $performanceService;
    }

    /**
     * GET /performance/scores
     */
    public function scores(Request $request)
    {
        $user = $request->user();
        if (!$user || !$user->player) {
            return response()->json(['error' => 'Player profile not found'], 404);
        }

        try {
            $result = $this->performanceService->getPerformanceScores($user->player->PlayerId);

            if (!$result) {
                return response()->json(['error' => 'Scores not found'], 404);
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}