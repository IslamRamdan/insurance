<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaTransaction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'visa_count',
        'amount',
        'fawaterk_invoice_id',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
