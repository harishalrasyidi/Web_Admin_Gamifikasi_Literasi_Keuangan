<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterventionTemplate extends Model
{
    protected $table = 'interventiontemplates';
    protected $primaryKey = 'level';
    public $timestamps = false;

    protected $fillable = [
        'level',
        'risk_level',
        'title_template',
        'message_template',
        'actions_template',
        'is_mandatory'
    ];

    protected $casts = [
        'actions_template' => 'array'
    ];
}