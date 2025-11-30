<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Scenario;
use App\Services\ScenarioService; // Service logika utama
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScenarioController extends Controller
{
    protected $service;

    public function __construct(ScenarioService $service)
    {
        $this->service = $service;
    }

    /
    public function index(Request $request)
    {
        $scenarios = Scenario::select(
            'id',
            'title',
            'category',
            'difficulty',
            'created_at'
        )
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($scenarios);
    }


    public function show(Request $request, Scenario $scenario)
    {
        $scenario->load('options');

        $intervention = $this->shouldShowIntervention(
            $request->input('player_id'),
            $request->input('session_id')
        );

        return response()->json([
            'category' => $scenario->category,
            'title' => $scenario->title,
            'question' => $scenario->question,
            'options' => $scenario->options->map(function ($option) {
                return [
                    'id' => $option->optionId,
                    'text' => $option->text
                ];
            }),
            'intervention' => $intervention
        ], 200);
    }

    private function shouldShowIntervention($playerId, $sessionId)
    {
        if (!$playerId) {
            return false;
        }

        $recentDecisions = \App\Models\PlayerDecision::where('player_id', $playerId)
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $errorStreak = 0;
        foreach ($recentDecisions as $decision) {
            if (!$decision->is_correct) {
                $errorStreak++;
            } else {
                break;
            }
        }

        return $errorStreak >= 3;
    }

    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scenario_id' => 'required|string|exists:scenarios,id',
            'selected_option' => 'required|string',
            'decision_time_seconds' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'details' => $validator->errors()
            ], 422);
        }

        try {
            $result = $this->service->processSubmission($validator->validated());

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Processing failed',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}