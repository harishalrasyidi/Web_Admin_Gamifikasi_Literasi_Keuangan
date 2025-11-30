<?php

namespace App\Repositories;

use App\Models\PlayerProfile;

class PlayerProfileRepository
{
    public function findThresholdsByPlayerId(string $playerId)
    {
        return PlayerProfile::where('PlayerId', $playerId)
            ->select('PlayerId', 'thresholds')
            ->first();
    }

    public function updateScores(string $playerId, array $newScores)
    {
        return PlayerProfile::where('PlayerId', $playerId)
            ->update(['lifetime_scores' => $newScores]);
    }

    public function findProfile(string $playerId)
    {
        return PlayerProfile::where('PlayerId', $playerId)->first();
    }

    public function updateThresholds(string $playerId, array $newThresholds, ?string $reason = null)
    {
        $data = ['thresholds' => $newThresholds];

        if ($reason) {
            $data['last_threshold_update_reason'] = $reason;
        }

        return PlayerProfile::where('PlayerId', $playerId)
            ->update($data);
    }
    public function findFullProfile(string $playerId)
    {
        return PlayerProfile::where('PlayerId', $playerId)->first();
    }
}