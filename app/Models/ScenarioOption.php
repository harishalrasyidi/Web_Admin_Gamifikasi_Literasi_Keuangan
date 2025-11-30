<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScenarioOption extends Model
{
    use HasFactory;

    protected $table = 'scenario_options';
    public $timestamps = false;

    protected $casts = [
        'scoreChange' => 'array',
    ];

    public function scenario()
    {
        return $this->belongsTo(Scenario::class, 'scenarioId', 'id');
    }
}