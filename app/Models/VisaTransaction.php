<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaTransaction extends Model
{
    //
    protected $fillable = [
        'user_id',
        'amount',
        'visa_count', // أضفه هنا
        'status',
        'fawaterk_invoice_id'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
