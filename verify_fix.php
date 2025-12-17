<?php

use Illuminate\Contracts\Console\Kernel;

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';

$app->make(Kernel::class)->bootstrap();

try {
    $playerId = 'player_dummy_profiling_infinite'; // Use the ID from the error log

    // Ensure player exists exclusively for this test if needed, but error log implies it exists
    $player = \App\Models\Player::find($playerId);
    if (!$player) {
        echo "Player $playerId not found, creating dummy...\n";
        // Create logical dummy if not exists, but likely exists
        $playerId = \App\Models\Player::first()->PlayerId;
    }

    $pd = \App\Models\PlayerDecision::create([
        'player_id' => $playerId,
        'session_id' => null, // Testing the fix
        'turn_number' => 0,
        'content_id' => 'test_null_check',
        'content_type' => 'test',
        'selected_option' => 'A',
        'is_correct' => false,
        'score_change' => 0,
        'decision_time_seconds' => 1,
        'created_at' => now()
    ]);

    echo "SUCCESS: Created PlayerDecision with null session_id. ID: " . $pd->id . "\n";

    // Cleanup
    $pd->delete();

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
