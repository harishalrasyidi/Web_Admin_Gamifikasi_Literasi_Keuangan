<?php

namespace App\Services;

use App\Repositories\PlayerDecisionRepository;
use App\Repositories\PlayerProfileRepository;
use App\Models\ScenarioOption;
use App\Models\Scenario;

class ScenarioService
{
    protected $decisionRepo;
    protected $profileRepo;

    public function __construct(
        PlayerDecisionRepository $decisionRepo,
        PlayerProfileRepository $profileRepo
    ) {
        $this->decisionRepo = $decisionRepo;
        $this->profileRepo = $profileRepo;
    }

    public function processSubmission(array $data)
    {
        $option = ScenarioOption::where('scenarioId', $data['scenario_id'])
            ->where('optionId', $data['selected_option'])
            ->first();

        if (!$option) {
            throw new \Exception("Opsi jawaban tidak ditemukan.");
        }

        $scoreChangeArray = $option->scoreChange;

        $affectedScore = array_key_first($scoreChangeArray);
        $scoreChange = $scoreChangeArray[$affectedScore] ?? 0;

        $newScoreValue = 12;

        $responseMessage = $option->is_correct
            ? ($option->feedback ?? "Pilihan yang tepat!")
            : ($option->feedback ?? "Hati-hati, keputusan ini berdampak negatif.");

        return [
            'correct' => $option->is_correct,
            'score_change' => $scoreChange,
            'affected_score' => $affectedScore,
            'new_score_value' => $newScoreValue,
            'response' => $responseMessage
        ];
    }
}