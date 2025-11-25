<?php

namespace App\Services;

use App\Models\PlayerProfile;
use App\Services\AI\CosineSimilarityService;
use Illuminate\Support\Facades\DB;

class RecommendationService
{
    protected $cosine;

    public function __construct(CosineSimilarityService $cosine)
    {
        $this->cosine = $cosine;
    }

    /**
     * Logika 1: Cosine Similarity untuk Pertanyaan Selanjutnya
     */
    public function recommendNextQuestion(string $playerId)
    {
        $profile = PlayerProfile::find($playerId);
        if (!$profile || empty($profile->lifetime_scores)) {
            return ['error' => 'Player profile or scores not found'];
        }

        $userScores = json_decode($profile->lifetime_scores, true);
        
        $weakestCategory = $this->findWeakestCategory($userScores);
        $userWeakestScore = $userScores[$weakestCategory] ?? 0;

        $questions = DB::table('scenarios')
                        ->where('category', $weakestCategory)
                        ->get();

        if ($questions->isEmpty()) {
            return ['error' => 'No questions found for category: ' . $weakestCategory];
        }

        $bestQuestion = null;
        $maxSimilarity = -1;
        $userVector = $this->prepareVector($userScores);
        
        foreach ($questions as $question) {
            if ($question->difficulty <= $userWeakestScore) {
                continue;
            }

            $questionVector = $this->createQuestionVector($question->category, $question->difficulty, array_keys($userScores));
            $similarity = $this->cosine->calculate($userVector, $questionVector);

            if ($similarity > $maxSimilarity) {
                $maxSimilarity = $similarity;
                $bestQuestion = $question;
            }
        }

        if ($bestQuestion) {
            return [
                'recommendation' => $bestQuestion,
                'similarity_score' => $maxSimilarity,
                'reason' => "Based on your lowest score in $weakestCategory"
            ];
        }

        return ['error' => 'No suitable challenging question found'];
    }

    /**
     * Logika 2: Rekomendasi Path Pembelajaran
     */
    public function getRecommendationPath(string $playerId)
    {
        $profile = PlayerProfile::find($playerId);
        if (!$profile) return null;

        $weakAreas = json_decode($profile->weak_areas, true) ?? ['general_basics'];
        
        $steps = [];
        $phase = 1;
        foreach ($weakAreas as $area) {
            $steps[] = [
                'phase' => $phase++,
                'focus' => ucwords(str_replace('_', ' ', $area)),
                'estimated_time' => '2 sesi',
                'estimated_gain' => '+10 poin'
            ];
        }

        return [
            'player_id' => $playerId,
            'title' => 'Personalized Learning Path',
            'steps' => $steps
        ];
    }


    /**
     * Logika 3: Perbandingan Peer
     */
    public function getPeerComparison(string $playerId)
    {
        // 1. Ambil Profil Pemain Saat Ini
        $currentPlayer = PlayerProfile::find($playerId);
        if (!$currentPlayer || empty($currentPlayer->lifetime_scores)) {
            return null;
        }

        // Hitung skor overall pemain ini
        $currentScoresRaw = json_decode($currentPlayer->lifetime_scores, true);
        $playerScore = $this->calculateOverall($currentScoresRaw);

        // 2. Ambil Populasi Skor (Bisa di-cache untuk performa)
        $allProfiles = PlayerProfile::whereNotNull('lifetime_scores')->pluck('lifetime_scores');

        $allOverallScores = [];
        foreach ($allProfiles as $jsonScore) {
            $scores = json_decode($jsonScore, true);
            if (is_array($scores)) {
                $allOverallScores[] = $this->calculateOverall($scores);
            }
        }

        if (empty($allOverallScores)) {
            $allOverallScores = [0];
        }

        // 3. Hitung Statistik Populasi
        $count = count($allOverallScores);
        $average = array_sum($allOverallScores) / $count;
        
        sort($allOverallScores);

        // Hitung Percentile (Rank)
        $rank = 0;
        foreach ($allOverallScores as $s) {
            if ($s < $playerScore) $rank++;
        }
        $percentile = ($count > 1) ? ($rank / ($count - 1)) * 100 : 100;

        // Hitung Top 10% Threshold (90th Percentile)
        $top10Index = floor(0.9 * $count);
        $top10Index = min($top10Index, $count - 1);
        $top10Threshold = $allOverallScores[$top10Index];

        // 4. Generate Insights (Kata-kata Mutiara/Saran)
        $insights = [];
        
        // Insight 1: Posisi Rata-rata
        if ($playerScore >= $average) {
            $insights[] = "Skor kamu di atas rata-rata pemain lain! 👍";
        } else {
            $insights[] = "Skor kamu sedikit di bawah rata-rata, yuk kejar ketertinggalan!";
        }

        // Insight 2: Target Top 10%
        if ($playerScore < $top10Threshold) {
            $gap = round($top10Threshold - $playerScore);
            $insights[] = "Butuh +{$gap} poin lagi untuk masuk jajaran Top 10% Elite.";
        } else {
            $insights[] = "Luar biasa! Kamu termasuk dalam Top 10% pemain terbaik.";
        }

        // Insight 3: Saran Spesifik berdasarkan Kelemahan
        $weakAreas = json_decode($currentPlayer->weak_areas, true) ?? [];
        if (!empty($weakAreas)) {
            $weakest = ucwords(str_replace('_', ' ', $weakAreas[0]));
            $insights[] = "Pemain yang fokus memperbaiki '$weakest' biasanya naik level 45% lebih cepat.";
        }

        // 5. Return Format
        return [
            'player_score' => round($playerScore),
            'average' => round($average),
            'percentile' => round($percentile),
            'top_10_threshold' => round($top10Threshold),
            'insights' => $insights
        ];
    }

    /**
     * Helper: Hitung rata-rata dari array skor kategori
     */
    private function calculateOverall(array $scores): float
    {
        // Filter hanya nilai numerik untuk keamanan
        $numericScores = array_filter($scores, 'is_numeric');
        
        if (empty($numericScores)) return 0.0;
        
        return array_sum($numericScores) / count($numericScores);
    }
    
    /**
     * Mencari kategori dengan skor terendah
     */
    private function findWeakestCategory(array $scores): string
    {
        asort($scores);
        return array_key_first($scores);
    }

    /**
     * Mempersiapkan vektor dari skor kategori untuk perhitungan cosine similarity
     */
    private function prepareVector(array $scores): array
    {
        $categories = ['pendapatan', 'anggaran', 'tabungan_dan_dana_darurat', 'utang', 'investasi', 'asuransi_dan_proteksi', 'tujuan_jangka_panjang'];
        $vector = [];
        foreach ($categories as $cat) {
            $vector[] = $scores[$cat] ?? 0;
        }
        return $vector;
    }

    /**
     * Membuat vektor pertanyaan berdasarkan kategori dan tingkat kesulitan
     */
    private function createQuestionVector(string $category, float $difficulty, array $allCategories): array
    {
        $normalizedCategory = strtolower(str_replace([' ', '&'], ['_', 'dan'], $category));
        
        $vector = [];
        $template = array_fill_keys(['pendapatan', 'anggaran', 'tabungan_dan_dana_darurat', 'utang', 'investasi', 'asuransi_dan_proteksi', 'tujuan_jangka_panjang'], 0);
        
        if (array_key_exists($normalizedCategory, $template)) {
            $template[$normalizedCategory] = $difficulty;
        }
        
        return array_values($template);
    }
}
