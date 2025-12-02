<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dokumentasi API - Gamifikasi Literasi Keuangan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .method-get { @apply bg-blue-100 text-blue-700 border-blue-300; }
        .method-post { @apply bg-green-100 text-green-700 border-green-300; }
        .method-put { @apply bg-yellow-100 text-yellow-700 border-yellow-300; }
        .method-delete { @apply bg-red-100 text-red-700 border-red-300; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div class="container mx-auto p-6 max-w-5xl">
        
        <!-- Header -->
        <header class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">API Documentation</h1>
            <p class="text-gray-600">Gamifikasi Literasi Keuangan (Versi 3)</p>
            <div class="mt-4 inline-block bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200">
                <span class="text-sm font-semibold text-gray-500">Base URL:</span>
                <code class="ml-2 text-indigo-600 font-mono">http://127.0.0.1:8000/api</code>
            </div>
        </header>

        <!-- Auth Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-key mr-2 text-yellow-500"></i> Authentication & Config
            </h2>
            <div class="space-y-3">
                <!-- POST /auth/google -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                            <span class="font-mono text-sm font-semibold">/auth/google</span>
                            <span class="text-sm text-gray-500">- Login dengan Google ID Token</span>
                        </div>
                    </div>
                </div>
                <!-- POST /auth/refresh -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                            <span class="font-mono text-sm font-semibold">/auth/refresh</span>
                            <span class="text-sm text-gray-500">- Perbarui Access Token</span>
                        </div>
                    </div>
                </div>
                <!-- GET /config/game -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between cursor-pointer hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                            <span class="font-mono text-sm font-semibold">/config/game</span>
                            <span class="text-sm text-gray-500">- Ambil konfigurasi global</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Profiling Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-user-circle mr-2 text-blue-500"></i> Profiling (AI)
            </h2>
            <div class="space-y-3">
                <!-- GET /profiling/status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                            <span class="font-mono text-sm font-semibold">/profiling/status</span>
                            <span class="text-sm text-gray-500">- Cek status profiling pemain</span>
                        </div>
                        <i class="fas fa-lock text-gray-300 text-xs" title="Butuh Token"></i>
                    </div>
                </div>
                <!-- POST /profiling/submit -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                            <span class="font-mono text-sm font-semibold">/profiling/submit</span>
                            <span class="text-sm text-gray-500">- Kirim jawaban kuesioner</span>
                        </div>
                        <i class="fas fa-lock text-gray-300 text-xs" title="Butuh Token"></i>
                    </div>
                </div>
                <!-- GET /profiling/cluster -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                            <span class="font-mono text-sm font-semibold">/profiling/cluster</span>
                            <span class="text-sm text-gray-500">- Hitung & ambil hasil klaster AI</span>
                        </div>
                        <i class="fas fa-lock text-gray-300 text-xs" title="Butuh Token"></i>
                    </div>
                </div>
                <!-- GET /profiling/details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center justify-between hover:bg-gray-50">
                        <div class="flex items-center gap-4">
                            <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                            <span class="font-mono text-sm font-semibold">/profiling/details</span>
                            <span class="text-sm text-gray-500">- Detail profil lengkap</span>
                        </div>
                        <i class="fas fa-lock text-gray-300 text-xs" title="Butuh Token"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Matchmaking Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-users mr-2 text-green-500"></i> Matchmaking & Lobby
            </h2>
            <div class="space-y-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/matchmaking/join</span>
                        <span class="text-sm text-gray-500">- Gabung/Buat Lobi</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/matchmaking/status</span>
                        <span class="text-sm text-gray-500">- Polling status lobi</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/matchmaking/character/select</span>
                        <span class="text-sm text-gray-500">- Pilih Avatar</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/matchmaking/ready</span>
                        <span class="text-sm text-gray-500">- Set status siap main</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Gameplay Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-gamepad mr-2 text-purple-500"></i> Gameplay Session
            </h2>
            <div class="space-y-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/start</span>
                        <span class="text-sm text-gray-500">- Mulai Game (Host Only)</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/session/state</span>
                        <span class="text-sm text-gray-500">- Ambil status papan permainan</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/ping</span>
                        <span class="text-sm text-gray-500">- Keep-alive & Sync Time</span>
                    </div>
                </div>
            </div>
        </section>

         <!-- Turn Action Section -->
         <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-dice mr-2 text-red-500"></i> Turn & Actions
            </h2>
            <div class="space-y-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/turn/start</span>
                        <span class="text-sm text-gray-500">- Mulai Giliran</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/turn/roll</span>
                        <span class="text-sm text-gray-500">- Lempar Dadu</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/player/move</span>
                        <span class="text-sm text-gray-500">- Gerakkan Pion</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/session/turn/current</span>
                        <span class="text-sm text-gray-500">- Info Turn Aktif (Untuk semua player)</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/session/turn/end</span>
                        <span class="text-sm text-gray-500">- Akhiri Giliran</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recommendation Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-lightbulb mr-2 text-yellow-600"></i> Recommendation & Content
            </h2>
            <div class="space-y-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/recommendation/next</span>
                        <span class="text-sm text-gray-500">- Rekomendasi Skenario (Cosine Sim)</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/recommendation/path</span>
                        <span class="text-sm text-gray-500">- Learning Path</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/recommendation/peer</span>
                        <span class="text-sm text-gray-500">- Perbandingan Skor Peer</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/tile/{tile_id}</span>
                        <span class="text-sm text-gray-500">- Ambil Info Tile & Trigger Event</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/scenario/{id}</span>
                        <span class="text-sm text-gray-500">- Detail Skenario & Intervensi</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/scenario/submit</span>
                        <span class="text-sm text-gray-500">- Jawab Skenario</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/card/quiz/{id}</span>
                        <span class="text-sm text-gray-500">- Detail Kuis</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/card/quiz/submit</span>
                        <span class="text-sm text-gray-500">- Jawab Kuis</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Misc Section -->
        <section class="mb-8">
            <h2 class="text-xl font-bold mb-4 border-b pb-2 text-gray-700 flex items-center">
                <i class="fas fa-chart-line mr-2 text-pink-500"></i> Miscellaneous
            </h2>
            <div class="space-y-3">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/performance/scores</span>
                        <span class="text-sm text-gray-500">- Skor Performa</span>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/leaderboard</span>
                        <span class="text-sm text-gray-500">- Papan Peringkat</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-get">GET</span>
                        <span class="font-mono text-sm font-semibold">/intervention/trigger</span>
                        <span class="text-sm text-gray-500">- Cek Peringatan Risiko</span>
                    </div>
                </div>
                 <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 flex items-center gap-4 hover:bg-gray-50">
                        <span class="px-3 py-1 rounded text-xs font-bold border method-post">POST</span>
                        <span class="font-mono text-sm font-semibold">/feedback/intervention</span>
                        <span class="text-sm text-gray-500">- Log Respons Intervensi</span>
                    </div>
                </div>
            </div>
        </section>

    </div>

</body>
</html>