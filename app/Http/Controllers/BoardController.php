<?php

namespace App\Http\Controllers;

use App\Services\BoardService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    protected $boardService;

    public function __construct(BoardService $boardService)
    {
        $this->boardService = $boardService;
    }

    /**
     * GET /tile/{tile_id}
     * Mengambil info kotak dan memicu event.
     * Catatan: {tile_id} di URL ini merepresentasikan position_index (int)
     */
    public function getTile(Request $request, $tileId)
    {
        $user = $request->user();
        if (!$user || !$user->player) {
            return response()->json(['error' => 'Player profile not found'], 404);
        }

        try {
            // Konversi ke integer karena kita pakai index
            $tileIndex = (int) $tileId;

            $result = $this->boardService->resolveTileEvent($user->player->PlayerId, $tileIndex);

            if (isset($result['error'])) {
                return response()->json($result, 400);
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
