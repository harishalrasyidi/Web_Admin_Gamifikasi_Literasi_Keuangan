<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scenario extends Model
{
    use HasFactory;

    protected $table = 'scenarios';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    
    protected $casts = [
        'tags' => 'array',
        'weak_area_relevance' => 'array',
        'cluster_relevance' => 'array',
    ];

    public function options()
    {
        return $this->hasMany(ScenarioOption::class, 'scenarioId', 'id');
    }
}