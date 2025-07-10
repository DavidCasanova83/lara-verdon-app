<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'city',
        'country',
        'department',
        'email',
        'consent_newsletter',
        'consent_data_processing',
        'profile',
        'age_groups',
        'specific_requests',
        'general_requests',
        'other_request'
    ];

    protected $casts = [
        'age_groups' => 'array',
        'specific_requests' => 'array',
        'general_requests' => 'array',
        'consent_newsletter' => 'boolean',
        'consent_data_processing' => 'boolean'
    ];
}
