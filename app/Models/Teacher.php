<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Models\School;

use App\Models\SchoolTeacher;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends BaseModel
{
    use HasFactory, SoftDeletes;

    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'school_id',
        'visibility_id',
        'gender_id',
        'lastname',
        'middlename',
        'firstname',
        'birth_date',
        'phone',
        'mobile',
        'email',
        'street',
        'street_number',
        'street2',
        'zip_code',
        'place',
        'country_code',
        'province_id',
        'geo_latitude',
        'geo_longitude',
        'type',
        'comment',
        'profile_image_id',
        'has_user_account',
        'teacher_cv',
        'licence_arp',
        'licence_usp',
        'licence_js',
        'bank_iban',
        'bank_account',
        'bank_swift',
        'bank_name',
        'bank_address',
        'bank_zipcode',
        'bank_place',
        'bank_country_code',
        'bank_province_id',
        'bg_color_agenda',
        'billing_street',
        'billing_street_number',
        'billing_street2',
        'billing_zip_code',
        'billing_place',
        'billing_country_code',
        'billing_province_id',
        'about_text',
        'display_home_flag',
        'billing_method',
        'billing_amount',
        'billing_method_eff_date',
        'billing_currency',
        'billing_date_start',
        'billing_date_end',
        'invoice_process_day_no',
        'person_language_preference',
        'tax_desc',
        'tax_perc',
        'tax_applicable',
        'etransfer_acc',
        'cheque_payee',
        'tax_number',
        'invoice_opt_activated',
        'is_active',
        'created_by',
        'modified_by'
    ];

  

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'date:Y/m/d H:i',
        'modified_at' => 'date:Y/m/d H:i',
    ];


    protected $appends = [];

     /**
     * Get the schools for the Teachers.
     */
    public function schools()
    {
        return $this->belongsToMany(School::class)
                    ->withPivot('id', 'nickname', 'licence_js', 'role_type', 'is_teacher', 'has_user_account', 'bg_color_agenda', 'comment', 'is_active', 'created_at','deleted_at');
    }

    /**
     * Get the user account.
     */
    public function user()
    {
        return $this->morphOne(User::class, 'personable','person_type', 'person_id');
    }

     /**
     * Get the schools for the teacher.
     */
    public function schoolData()
    {
        
        return $this->hasMany(SchoolTeacher::class)
            ->join('schools as u', 'u.id', '=', 'school_teachers.school_id')
            ->select(['u.*']);

    }

   

}

