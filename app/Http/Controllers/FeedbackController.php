<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// Force load to bypass potential autoloader cache issues
$servicePath = __DIR__ . '/../../Services/FeedbackService.php';
if (file_exists($servicePath)) {
    require_once $servicePath;
}

class FeedbackController extends Controller
{
    protected $feedbackService;

    public function __construct(FeedbackService $feedbackService)
    {
        $this->feedbackService = $feedbackService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'intervention_id' => 'required|string',
            'scenario_id' => 'required|string',
            'player_response' => 'required|string'
        ]);

        $user = $request->user();
        if (!$user || !$user->player) {
            return response()->json(['error' => 'Player profile not found'], 404);
        }

        try {
            $result = $this->feedbackService->storeInterventionFeedback(
                $user->player->PlayerId,
                $validated
            );

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}