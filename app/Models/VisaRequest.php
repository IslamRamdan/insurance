<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisaRequest extends Model
{
    //
    protected $fillable = [
        // الصور
        'image_passport',
        'image',

        // الاسم العربي
        'a_first_name',
        'a_father',
        'a_grand',
        'a_family',

        // الاسم الإنجليزي
        'e_first_name',
        'e_father',
        'e_grand',
        'e_family',

        // بيانات الجواز
        'passport_number',
        'card_id',
        'passport_issue_date',
        'passport_expiry_date',

        // بيانات شخصية
        'birth_place',
        'birth_date',
        'passport_issue_place',
        'sex',
        'job_or_relation_id',

        // العلاقات
        'user_id',
        'visa_application_id',
    ];

    /**
     * تحويل التواريخ تلقائيًا
     */
    protected $casts = [
        'passport_issue_date'  => 'date',
        'passport_expiry_date' => 'date',
        'birth_date'           => 'date',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function visaApplication()
    {
        return $this->belongsTo(VisaApplication::class);
    }
}
