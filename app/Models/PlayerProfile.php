<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlayerProfile extends Model
{
    use HasFactory;

    protected $table = 'playerprofile';
    protected $primaryKey = 'PlayerId';
    public $incrementing = false;

    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = null;
    const UPDATED_AT = 'last_updated';
    
    protected $fillable = [
        'PlayerId',
        'onboarding_answers',
        'cluster',
        'level',
        'traits',
        'weak_areas',
        'recommended_focus',
        'fuzzy_scores',
        'lifetime_scores',
        'decision_history',
        'behavior_pattern',
        'confidence_level',
        'fuzzy_scores',
        'ann_probabilities',
        'last_recommendation',
        'last_updated',
        'thresholds',
        'created_at',
        'updated_at'
    ];
    

    protected $casts = [
        'thresholds' => 'array',
        'lifetime_scores' => 'array',
        'onboarding_answers' => 'array',
        'traits' => 'array',
        'weak_areas' => 'array',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class, 'PlayerId', 'PlayerId');
    }
}
