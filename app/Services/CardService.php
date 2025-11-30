<?php

namespace App\Services;

// Impor model yang kita butuhkan
use App\Models\QuizCard;

class CardService
{
    /**
     * Mendapatkan kartu kuis berdasarkan ID beserta opsi-opsinya
     */
    public function getQuizCard(int $id)
    {
        return QuizCard::with('options')->findOrFail($id);
    }
}