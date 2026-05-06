<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaApplication extends Model
{
    //
    protected $fillable = [
        'sponsor_full_name',
        'sponsor_identity_number',
        'visa_type',
        'visa_number',
        'consulate_name',
        'status',
        'visa_application_id',
        "user_id"
    ];

    public function requests()
    {
        return $this->hasMany(VisaRequest::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
