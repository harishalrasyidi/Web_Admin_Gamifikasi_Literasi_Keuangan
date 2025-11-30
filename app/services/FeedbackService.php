<?php

namespace App\Services;

use App\Models\ParticipatesIn;
use App\Models\PlayerDecision;
use App\Repositories\FeedbackRepository;
use App\Services\ThresholdService;
use Illuminate\Support\Facades\Log;

class FeedbackService
{
    protected $feedbackRepo;
    protected $thresholdService;

    /*
     * Konstruktor untuk inisialisasi repository dan service terkait
    */
    public function storeInterventionFeedback(string $playerId, array $data)
    {
        $participation = ParticipatesIn::where('playerId', $playerId)
            ->whereHas('session', function ($query) {
                $query->whereIn('status', ['active', 'waiting']);
            })
            ->with('session')
            ->first();

        $sessionId = $participation ? $participation->sessionId : 'unknown_session';
        $turnNumber = $participation ? ($participation->session->current_turn ?? 0) : 0;

        PlayerDecision::create([
            'player_id' => $playerId,
            'session_id' => $sessionId,
            'turn_number' => $turnNumber,
            'content_id' => $data['scenario_id'] ?? 'general',
            'content_type' => 'intervention_log',
            'intervention_id' => $data['intervention_id'],
            'player_response' => $data['player_response'],
            'is_correct' => 0,
            'score_change' => 0,
            'created_at' => now()
        ]);

        Log::info("Feedback stored for player {$playerId}: Intervention {$data['intervention_id']} was {$data['player_response']}");

        return ['ok' => true];
    }
}