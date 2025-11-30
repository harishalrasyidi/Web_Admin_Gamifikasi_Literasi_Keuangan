<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PlayerService;

class PlayerController extends Controller
{
    protected $playerService;

    public function __construct(PlayerService $playerService)
    {
        $this->playerService = $playerService;
    }

    /* Memproses login via Google ID Token
    *  memvalidasi input, meneruskan data ke PlayerService untuk autentikasi,
    *  lalu mengembalikan respons JSON berisi hasil login atau error jika gagal.
    */
    public function googleLogin(Request $request)
    {
        $data = $request->validate([
            'google_id_token' => 'required|string',
            'platform' => 'nullable|string',
            'locale' => 'nullable|string'
        ]);

        try {
            $result = $this->playerService->loginWithGoogle($data);
            
            return response()->json($result, 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Authentication Failed',
                'message' => $e->getMessage()
            ], 401);
        }
    }
}