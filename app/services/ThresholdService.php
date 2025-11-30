<?php

namespace App\Services;

use App\Repositories\PlayerProfileRepository;

class ThresholdService
{
    protected $profileRepository;

    public function __construct(PlayerProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getPlayerThresholds(string $playerId)
    {
        $profile = $this->profileRepository->findThresholdsByPlayerId($playerId);
        if (!$profile) return null;
        return [
            'player_id' => $profile->PlayerId,
            'thresholds' => $profile->thresholds
        ];
    }

    public function increaseSensitivity(string $playerId)
    {
        $profile = $this->profileRepository->findThresholdsByPlayerId($playerId);
        if (!$profile || empty($profile->thresholds)) return false;

        $currentThresholds = $profile->thresholds;

        if (isset($currentThresholds['critical'])) {
            $currentThresholds['critical'] = min(0.95, $currentThresholds['critical'] + 0.05);
        }

        $reason = "system_auto_adjustment:ignored_warning";

        return $this->profileRepository->updateThresholds($playerId, $currentThresholds, $reason);
    }
    
    public function manualUpdate(string $playerId, array $adjustments)
    {
        $profile = $this->profileRepository->findThresholdsByPlayerId($playerId);
        if (!$profile) return false;
         
        $newThresholds = array_merge($profile->thresholds ?? [], $adjustments);
        $reason = $reason ?? "manual_update_by_admin";
         
        return $this->profileRepository->updateThresholds($playerId, $newThresholds, $reason);
    }
}