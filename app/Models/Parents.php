<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use App\Models\User;
use App\Models\SchoolStudent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parents extends BaseModel
{
    use HasFactory, SoftDeletes;
    protected $table = 'parents';
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'visibility_id',
        'gender_id',
        'lastname',
        'middlename',
        'firstname',
        'phone',
        'phone2',
        'mobile',
        'mobile2',
        'email',
        'email2',
        'street',
        'street_number',
        'street2',
        'zip_code',
        'country_code',
        'province_id',
        'profile_image_id',
        'has_user_account',
        'bank_iban',
        'bank_account',
        'bank_swift',
        'bank_name',
        'bank_address',
        'bank_zipcode',
        'bank_place',
        'bank_country_code',
        'bank_province_id',
        'level_date_arp',
        'level_date_usp',
        'nickname',
        'bg_color_agenda',
        'skating_group',
        'billing_street',
        'billing_street_number',
        'billing_street2',
        'billing_zip_code',
        'billing_place',
        'billing_country_id',
        'billing_province_id',
        'display_home_flag',
        'billing_method',
        'billing_amount',
        'billing_method_eff_date',
        'billing_currency',
        'billing_date_start',
        'billing_date_end',
        'invoice_process_day_no',
        'person_language_preference',
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
     * Get the user for the News.
     */
    public function student()
    {
        return $this->hasMany(ParentStudent::class);
    }


     /**
     * Get the schools for the teacher.
     */
    public function studentData()
    {
        return $this->hasMany(ParentStudent::class)
            ->join('students as u', 'u.id', '=', 'parent_students.student_id')
            ->select(['u.*']);
    }


    public function user()
    {
        return $this->morphOne(User::class, 'personable','person_type', 'person_id');
        //return $this->hasOne(User::class, 'person_id', 'id');
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'parent_students', 'parent_id', 'student_id')
                    ->withPivot('relations')
                    ->withTimestamps();
    }

}

